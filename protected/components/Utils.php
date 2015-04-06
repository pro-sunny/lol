<?php


class Utils {

    public static function getChampionImage( $champion_id )
    {
        $image = Yii::app()->db->createCommand()->select('image')->from('champion')->where('id=:id', array('id'=>$champion_id))->queryScalar();
        if( empty($image) ){
            $url = 'https://global.api.pvp.net/api/lol/static-data/euw/v1.2/champion/'.$champion_id.'?champData=image&api_key=';
            $response = Yii::app()->CURL->run($url.Yii::app()->params['key']);
            $data = CJSON::decode($response);
            Yii::app()->db->createCommand()->insert('champion', array('id'=>$champion_id, 'name'=>$data['name'], 'image'=>$data['image']['full']));

            $image = $data['image']['full'];
        }

        return self::getChampionImagePath( $image );
    }

    public static function getChampionImagePath( $image )
    {
        return Yii::app()->params['webRoot'].'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/champion/'.$image;
    }

    public static function getLeagueImagePath( $image )
    {
        return Yii::app()->params['webRoot'].'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/champion/'.$image;
    }

    public static function getItemImagePath( $item_id )
    {
        return Yii::app()->params['webRoot'].'/dragon_data/'.Yii::app()->params['dragonImagePath'].'/item/'.$item_id.'.png';

    }

} 