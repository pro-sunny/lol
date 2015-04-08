<?php


class Utils {

    public static $champions_data = array();

    public static function getChampionsData(){
        if( empty(self::$champions_data) ){
            $string = file_get_contents(Yii::app()->basePath.'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/data/championFull.json');
            $data = CJSON::decode($string);
            self::$champions_data = $data;
        }
        return self::$champions_data;
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

} 