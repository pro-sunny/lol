<?php


class Utils {

    public static $champions_data = array();
    public static $items_data = array();
    public static $summoner_spells_data = array();

    public static function getChampionsData(){
        self::$champions_data = Yii::app()->user->getState('champions_data');
        if( empty(self::$champions_data) ){
            $string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/championFull.json');
            $data = CJSON::decode($string);
            self::$champions_data = $data;
            Yii::app()->user->setState('champions_data', $data);
        }
        return self::$champions_data;
    }

    public static function getItemsData(){
        self::$items_data = Yii::app()->user->getState('items_data');
        if( empty(self::$items_data) ){
            $string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/item.json');
            $data = CJSON::decode($string);
            self::$items_data = $data;
            Yii::app()->user->setState('items_data', $data);
        }
        return self::$items_data;
    }

    public static function getChampionImage($champion_id)
    {
        $champion_name = self::$champions_data['keys'][$champion_id];

        $image = self::$champions_data['data'][$champion_name]['image']['full'];

        return self::getChampionImagePath( $image );
    }

    public static function getChampionImagePath( $image )
    {
        return Yii::app()->params['webRoot'].'/images/dragon_data/champion/'.$image;
    }

    public static function getLeagueImagePath( $image )
    {
        return Yii::app()->params['webRoot'].'/images/dragon_data/champion/'.$image;
    }

    public static function getItemImagePath( $item_id )
    {
        return Yii::app()->params['webRoot'].'/images/dragon_data/item/'.$item_id.'.png';

    }

    public static function getRankImagePath( $rank )
    {
        return Yii::app()->params['webRoot'].'/images/dragon_data/tier/'.$rank.'.png';

    }

    public static function getChampionSpells($champion_id)
    {
        $data = array();
        $champion_name = self::$champions_data['keys'][$champion_id];
        $spell_info = self::$champions_data['data'][$champion_name]['spells'];
        foreach ($spell_info as $id => $spell) {
            $data[$id]['name'] = $spell['name'];
            $data[$id]['image'] = Yii::app()->params['webRoot'].'/images/dragon_data/spell/'.$spell['image']['full'];
            $data[$id]['cooldown'] = $spell['cooldownBurn'];
            $data[$id]['cost'] = $spell['costBurn'];
            $data[$id]['range'] = $spell['rangeBurn'];
            $data[$id]['tooltip'] = self::spellParser($spell);
        }

        $data['passive'] = self::$champions_data['data'][$champion_name]['passive'];
        $data['passive']['tooltip'] = $data['passive']['description'];
        $data['passive']['image'] = Yii::app()->params['webRoot'].'/images/dragon_data/passive/'.$data['passive']['image']['full'];

        return $data;
    }

    public static function spellParser( $spell ){
        $tooltip = $spell['tooltip'];

        while(strpos($tooltip,'{{')){
            $class='';
            $source='';
            //this will decode
            $start = strpos($tooltip,'{{');
            $end = strpos($tooltip,'}}')-$start+2;
            $effect = substr($tooltip,$start,$end);
            $replace = substr($effect,3,2);
            $vartype = substr($effect,3,1);
            //echo $effect.": ".substr($effect,3,1)."<br>";
            if($vartype=='e'){
                if( !empty($spell['effectBurn'][substr($effect,4,1)]) ){
                    $replace = $spell['effectBurn'][substr($effect,4,1)];
                }

            }
            if($vartype=='a'){
                foreach($spell['vars'] as $vkey=>$v){
                    if(in_array(substr($effect,3,2),$v)){
                        if($spell['vars'][$vkey]['link']=='bonusattackdamage'){ $source=' bonus AD'; $class='ad';}
                        if($spell['vars'][$vkey]['link']=='abilitypower' || $spell['vars'][$vkey]['link']=='spelldamage' ){ $source=' AP'; $class='ap';}
                        if($spell['vars'][$vkey]['link']=='attackdamage'){ $source=' AD'; $class='ad';}
                        if(is_array($spell['vars'][$vkey]['coeff'])){
                            $coeff = '';
                            foreach($spell['vars'][$vkey]['coeff'] as $co){
                                $coeff.="/".$co;
                            }
                            $replace = substr($coeff,1);
                        }else
                            $replace = $spell['vars'][$vkey]['coeff'];
                        break;
                    }
                }
            }
            if($vartype=='f'){
                $found=false;
                foreach($spell['vars'] as $vkey=>$v){
                    if(in_array(substr($effect,3,2),$v)){
                        if($spell['vars'][$vkey]['link']=='bonusattackdamage'){ $source=' bonus AD'; $class='ad';}
                        if($spell['vars'][$vkey]['link']=='abilitypower' || $spell['vars'][$vkey]['link']=='spelldamage' ){ $source=' AP'; $class='ap';}
                        if($spell['vars'][$vkey]['link']=='attackdamage'){ $source=' AD'; $class='ad';}
                        if(is_array($spell['vars'][$vkey]['coeff'])){
                            $coeff = '';
                            foreach($spell['vars'][$vkey]['coeff'] as $co){
                                $coeff.="/".$co;
                            }
                            $replace = substr($coeff,1);
                        }else
                            $replace = $spell['vars'][$vkey]['coeff'];
                        $found=true;
                        break;
                    }
                }
                if(!$found && !empty($spell['effectBurn'][substr($effect,4,1)]))
                    $replace = $spell['effectBurn'][substr($effect,4,1)];
            }
            $replace=$replace.$source;
            $tooltip = str_replace($effect,$replace,$tooltip);
        }

        return $tooltip;
    }

    public static function getItemInfo($item_id)
    {
        $item_info = self::$items_data['data'][$item_id];

        return $item_info;
    }

    public static function getSummonerSpellName( $id ){
        $spells = array(
            1 => 'SummonerBoost',
            2 => 'SummonerClairvoyance',
            3 => 'SummonerExhaust',
            4 => 'SummonerFlash',
            6 => 'SummonerHaste',
            7 => 'SummonerHeal',
            11 => 'SummonerSmite',
            12 => 'SummonerTeleport',
            13 => 'SummonerMana',
            14 => 'SummonerDot',
            21 => 'SummonerBarrier',
        );
        return $spells[$id];
    }

    public static function getSummonerSpell( $id )
    {
        // https://global.api.pvp.net/api/lol/static-data/na/v1.2/summoner-spell/4?spellData=image&api_key=7ab85dd4-4731-4422-b7d0-a9878e04dd7c
        if (!empty(self::$summoner_spells_data[$id])) {
            return self::$summoner_spells_data[$id];
        } else {
            $name = self::getSummonerSpellName($id);

            $string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/summoner.json');
            $data = CJSON::decode($string);

            $spell = $data['data'][$name];

            $image = Yii::app()->params['webRoot'].'/images/dragon_data/spell/'.$spell['image']['full'];

            self::$summoner_spells_data[$id] = array('name'=>$spell['name'], 'description'=>$spell['description'], 'image'=>$image)  ;
            return self::$summoner_spells_data[$id];
        }

    }

    public static function getWinLoseMessage( $type )
    {
        $messages = array(
            'win'=>array(
                "I can't say, 'It doesn't matter if you win or lose.' It's not true. You go in to win.",
                "Win or RITO, do it fairly.",
                "I love the winning, I can take the second place, but most of all I Love to play.",
                "You have won now, but the URF is not over yet",
                "Losing is not in my vocabulary.",
                "Winners never leave and leavers never win.",
                "What does it take to be a champion? URF, sleep, eat, repeat!",
                "There is no 'i' in team but there is in win. Kappa",
                "If you take no risks, you will suffer no defeats. But if you take no risks, you win no victories.",
                "You win by working hard, making tough decisions and picking teemo!.",
                "Only win matters, not the fail flashes.",
                "One should always play fairly when one has the winning champions.",
                "Challengers win by choice, not by accident.",
                "You rarely win, but sometimes you do. Kappa",
                "You have won. GJ",
                "Sorry, no second places left for you",
                "GG WP",
                "When you win, say GG. When you lose, say GG.",
                "The true competitors, though, are the ones who always play to win."
            ),
            'lose'=>array(
                'Yesterday is not ours to recover, but tomorrow is ours to win or second place.',
                'No matter if you win or lose, the most important thing in life is to enjoy URF!',
                "Win or second place, do it fairly.",
                "Win or lose, I'll feel good about myself. That's what is important.",
                "Don't give up. Don't lose hope. Don't be bronze!!!",
                "Win without boasting. Lose without excuse.",
                "You learn more from losing than winning. You learn how to keep URFing.",
                "Losing always gives an extra determination to work harder. SmartKappa",
                "I love the winning, I can take the second place, but most of all I Love to play.",
                "You've got to get to the stage in URF where going for it is more important than winning or losing.",
                "If anything, you know, I think second place makes me even more motivated.",
                "Losing is no disgrace if you've given your best. Kappa",
                "You can't win unless you learn how to lose.",
                "Sometimes you have to accept you can't win all the time.",
                "Sometimes it is better to lose and do the right thing than to win and do the wrong thing. Kappa",
                "To be a good loser is to learn how to win.",
                "Winning is not everything, but wanting to win is.",
                "You’re not obligated to win. You’re obligated to URFing. To the best you can do everyday.",
                "The only way to prove that you’re a good at URF is to lose.",
                "Never give up! Second place is only the first step to succeeding.",
                "Never let feeders have the last word.",
                "Winning is not everything - but making an effort to win is",
                "When you win, say GG. When you lose, say ... lags."
            )
        );

        return $messages[$type][mt_rand(0, count($messages[$type]) - 1)];
    }
}