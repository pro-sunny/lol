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


        // $question_type = array_rand(Yii::app()->params['question_types']);
        $question_type = Yii::app()->params['question_types'][0];

//         Yii::app()->db->createCommand()->insert('user_match', array('user_id'=>Yii::app()->user->id, 'match_id'=>$match['id'], 'question_type'=>$question_type));
        Yii::app()->user->setFlash('match_id', $match['id']);
        Yii::app()->user->setFlash('question_type', $question_type);

        $this->render('index', array('match_id'=>$match['id'], 'region'=>$match['region']));

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);

        echo $execution_time;
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
        $match_id = Yii::app()->user->getFlash('match_id');
        $question_type = Yii::app()->user->getFlash('question_type');

        $data = array('damage_done'=>array(), 'damage_taken'=>array());

        $summoners = CJSON::decode(Yii::app()->db->createCommand()->select('participants')->from('match')->where('id=:id', array('id'=>$match_id))->queryScalar());
        foreach ($summoners as $summoner) {
            $data['damage_done'][$summoner['participantId']] = $summoner['stats']['totalDamageDealtToChampions'];
            $data['damage_taken'][$summoner['participantId']] = $summoner['stats']['totalDamageTaken'];
        }

        $the_one = 0;
        if ( $question_type == 'highest_damage_dealt' ) {
            $the_one = array_keys($data['damage_done'], max($data['damage_done']));
            $the_one = $the_one[0];
        }


        if ($id == $the_one) {
            echo 'win';
        } else {
            echo 'second place';
        }

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