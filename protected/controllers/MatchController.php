<?php

class MatchController extends Controller
{

    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('index'),
                'users'=>array('@'),
            ),
            array('allow',
                'actions'=>array('view','delete'),
                'roles'=>array('admin'),
            ),
            // ???
            array('deny',
                'actions'=>array('delete'),
                'users'=>array('*'),
            ),
        );
    }

	public function actionIndex()
	{

        $time_start = microtime(true);

        $match = $this->getRandomUserMatch( Yii::app()->user->id );


        $questions = Yii::app()->params['question_types'];
        $question_type = array_rand($questions, 1);

        // $question_type = array_rand(Yii::app()->params['question_types']);
//        $question_type = Yii::app()->params['question_types'][0];

        Yii::app()->db->createCommand()->insert('user_match', array('user_id'=>Yii::app()->user->id, 'match_id'=>$match['id'], 'question_type'=>$question_type));
//        Yii::app()->user->setFlash('match_id', $match['id']);
        Yii::app()->user->setState('match_id', $match['id']);
        Yii::app()->user->setState('question_type', $question_type);

        $this->render('index', array('match_id'=>$match['id'], 'region'=>$match['region'], 'question'=>$questions[$question_type]));

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
	}

    public function getRandomUserMatch( $user_id )
    {
        // $offset = Yii::app()->db->createCommand(" SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM `match` ")->queryScalar();
        // $match = Yii::app()->db->createCommand(" SELECT * FROM `match` WHERE participants != '' LIMIT $offset, 1 ")->queryRow();

        $matches = Yii::app()->db->createCommand()->from('match')->where('participants != ""')->queryAll();
        $match = $matches[mt_rand(0, count($matches) - 1)];

        $user_match = Yii::app()->db->createCommand()->from('user_match')->where('user_id=:user_id AND match_id=:match_id', array('user_id'=>$user_id, 'match_id'=>$match['id']))->queryRow();

        if ($user_match) {
            $match = $this->getRandomUserMatch( $user_id );
        }

        return $match;
    }

    public function actionCheckAnswer()
    {
        $id = Yii::app()->request->getPost('id');
        $db = Yii::app()->db;
//        $match_id = Yii::app()->user->getFlash('match_id');
        $match_id = Yii::app()->user->getState('match_id');
        $question_type = Yii::app()->user->getState('question_type');

        $user = Yii::app()->db->createCommand()->from('user')->where('id=:id', array('id'=>Yii::app()->user->id))->queryRow();
        $current_elo = $user['elo'];
        $wins = $user['wins'];
        $user['games']++;

        $data = array('damage_done'=>array(), 'damage_taken'=>array());

        $summoners = CJSON::decode($db->createCommand()->select('participants')->from('match')->where('id=:id', array('id'=>$match_id))->queryScalar());

        foreach ($summoners as $summoner) {
            $data['damage_done'][$summoner['participantId']] = $summoner['stats']['totalDamageDealtToChampions'];
            $data['damage_taken'][$summoner['participantId']] = $summoner['stats']['totalDamageTaken'];
        }

        $the_one = 0;
        if ( $question_type == 'highest_damage_dealt' ) {
            $the_one = array_keys($data['damage_done'], max($data['damage_done']));
            $the_one = $the_one[0];
        } elseif( $question_type == 'lowest_damage_dealt' ){
            $the_one = array_keys($data['damage_done'], min($data['damage_done']));
            $the_one = $the_one[0];
        } elseif( $question_type == 'highest_damage_taken' ){
            $the_one = array_keys($data['damage_taken'], max($data['damage_taken']));
            $the_one = $the_one[0];
        } elseif( $question_type == 'lowest_damage_taken' ){
            $the_one = array_keys($data['damage_taken'], min($data['damage_taken']));
            $the_one = $the_one[0];
        }

        $selected_champion = $summoners[$id-1]['championId'];
        $user_champion = $db->createCommand()->from('user_champion')
            ->where('user_id=:user_id AND champion_id=:champion_id', array('user_id'=>Yii::app()->user->id,  'champion_id'=>$selected_champion))
            ->queryRow();
        if( empty($user_champion) ){
            $db->createCommand()->insert('user_champion', array('user_id'=>Yii::app()->user->id, 'champion_id'=>$selected_champion, 'count'=>1));
        } else {
            $user_champion['count']++;
            $db->createCommand()->update('user_champion', array('count'=>$user_champion['count']), 'user_id=:user_id AND champion_id=:champion_id', array('user_id'=>Yii::app()->user->id,  'champion_id'=>$selected_champion));
        }

        $message = '';
        $elo_change = 0;

        $rank_name = ucfirst(Yii::app()->params['ranks'][$user['rank']]);

        if ($id == $the_one) {
            $status = 'win';
            $wins++;

            if( empty($user['promo']) ){
                $elo_change = 20 + rand(0, 5);
                $current_elo += $elo_change;
                $elo_change = '<span class="green-text">+'.$elo_change.'</span>';

                $message = Utils::getWinLoseMessage( 'win' );
                if( $current_elo >= 100 ){
                    $current_elo = 100;
                    $promo = array('won'=>0, 'played'=>0, 'games'=>3);
                    $message = "You are now applied to promotion games. Good luck";
                }
                $rank = $rank_name.': '.$current_elo.' LP ('.$elo_change.')';
            } else {
                $promo = json_decode($user['promo'], true);
                $promo['won']++;
                $promo['played']++;

                $rank = $rank_name.': promotion stage. Won: '.$promo['won'].' of '.$promo['games'];
                if( $promo['won'] == 2 && $promo['played'] >= ($promo['games'] - 1) ){
                    $user['rank']++;
                    $promo = '';
                    $current_elo = 0;
                    $message = "Congratulation you have been promoted to a higher rank!";

                    $rank_name = ucfirst(Yii::app()->params['ranks'][$user['rank']]);
                    $rank = $rank_name.': 0 LP.';
                } elseif( $promo['played'] == $promo['games'] ){
                    $current_elo -= 20 - rand(0, 5) + 20 - rand(0, 5);
                    $promo = '';
                    $message = "You have failed your promotion games.";
                    $rank = $rank_name.': '.$current_elo.' LP';
                }
            }

        } else {
            $status = 'lose';

            if( empty($user['promo']) ){
                $elo_change = 20 - rand(0, 5);
                $current_elo -= $elo_change;
                if( $current_elo <= 0 ){
                    $current_elo = 0;
                }
                $elo_change = '<span class="red-text">-'.$elo_change.'</span>';
                $rank = $rank_name.': '.$current_elo.' LP ('.$elo_change.')';

                $message = Utils::getWinLoseMessage( 'lose' );
            } else {
                $promo = json_decode($user['promo'], true);
                $promo['won']--;
                $promo['played']++;
                if( $promo['played'] == $promo['games'] ){
                    $current_elo -= 20 - rand(0, 5) + 20 - rand(0, 5);
                    $promo = '';
                    $message = "You have failed your promotion games.";
                }

                $rank = $rank_name.': promotion stage. Won: '.$promo['won'].' of '.$promo['games'];
            }

        }

        if( !empty($promo) ){
            $promo = json_encode($promo);
        } else {
            $promo = '';
        }



        $db->createCommand()->update('user',
            array('rank'=>$user['rank'], 'elo'=>$current_elo, 'promo'=>$promo, 'wins'=>$wins, 'games'=>$user['games']),
            'id=:id', array('id'=>Yii::app()->user->id)
        );

        echo json_encode( array('status'=>$status, 'elo_change'=>$elo_change, 'message'=>$message, 'rank'=>$rank) );
    }

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}