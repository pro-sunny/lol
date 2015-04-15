<?php
/**
 * Модель User
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $role
 * @property string $email
 * @property string $icon
 * @property string $rank
 * @property string $elo
 * @property string $wins
 */
class User extends CActiveRecord {
    const ROLE_ADMIN = 'administrator';
    const ROLE_MODER = 'moderator';
    const ROLE_USER = 'user';
    const ROLE_BANNED = 'banned';

    public $verifyCode;
    public $password_repeat;

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('email, login, password', 'required', 'on'=>'register'),
            array('login', 'length', 'min'=>'3', 'max'=>'30'),
            array('email, login', 'unique'),

            array('password', 'length', 'min'=>'6', 'max'=>'30'),
            array('password_repeat', 'compare', 'compareAttribute'=>'password', 'on'=>'register'),
            // email has to be a valid email address
            array('email', 'email'),
            array(
                'login', 'match', 'not' => true, 'pattern' => '/[^a-zA-Z0-9 ]/',
                'message' => 'Login must consist of letters, numbers and spaces only'
            ),
            array(
                'password', 'match', 'pattern' => '/[a-zA-Z0-9\s]/',
                'message' => 'Password must consist of letters, numbers and spaces only'
            )
        );
    }

    public function tableName(){
        return 'User';
    }

    protected function beforeSave(){
        $this->password = md5($this->password);
        return parent::beforeSave();
    }
}