<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
    protected $_id;

    public function authenticate(){
        $user = User::model()->find('LOWER(login)=?', array(strtolower($this->username)));
        if(($user===null) || (md5($this->password)!==$user->password)) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            $this->_id = $user->id;

            // using as Yii::app()->user->name
            // $this->username = $user->realName;

            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId(){
        return $this->_id;
    }
}