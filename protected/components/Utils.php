<?php


class Utils {

    public static $champions_data = array();
    public static $items_data = array();
    public static $summoner_spells_data = array();

    public static function getChampionsData(){
        if( empty(self::$champions_data) ){
            $string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/championFull.json');
            $data = CJSON::decode($string);
            self::$champions_data = $data;
        }
        return self::$champions_data;
    }

    public static function getItemsData(){
        if( empty(self::$items_data) ){
            $string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/item.json');
            $data = CJSON::decode($string);
            self::$items_data = $data;
        }
        return self::$items_data;
    }

    public static function getChampionImage( $champion_id )
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
                $replace = $spell['effectBurn'][substr($effect,4,1)];
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

    public static function getSummonerSpell( $id )
    {
        // https://global.api.pvp.net/api/lol/static-data/na/v1.2/summoner-spell/4?spellData=image&api_key=7ab85dd4-4731-4422-b7d0-a9878e04dd7c
        if (!empty(self::$summoner_spells_data[$id])) {
            return self::$summoner_spells_data[$id];
        } else {
            $url = 'https://global.api.pvp.net/api/lol/static-data/na/v1.2/summoner-spell/'.$id.'?spellData=image&api_key='.Yii::app()->params['key'];
            $response = Yii::app()->CURL->run($url);
            $api_data = CJSON::decode($response);

            $string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/summoner.json');
            $data = CJSON::decode($string);

            $spell = $data['data'][$api_data['key']];

            $image = Yii::app()->params['webRoot'].'/images/dragon_data/spell/'.$spell['image']['full'];

            self::$summoner_spells_data[$id] = array('name'=>$spell['name'], 'description'=>$spell['description'], 'image'=>$image)  ;
            return self::$summoner_spells_data[$id];
        }

    }
}