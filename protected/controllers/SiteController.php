<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
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

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

    public function actionGetContent(){
        $id = Yii::app()->request->getPost('id');
        echo 'THIS html <br> SO '.$id.'<img alt="" src="/images/dragon_data/champion/'.$id.'.png">';
    }

    public function actionGetSpell()
    {
        $champion_id = Yii::app()->request->getPost('champion_id');
        $spell_id = Yii::app()->request->getPost('spell_id');

        $spell = Utils::getChampionSpells( $champion_id );
        $spell = $spell[$spell_id];

        $this->renderPartial('spell', array('spell'=>$spell, 'spell_type'=>$spell_id));
    }

    public function actionGetItem()
    {
        $item_id = Yii::app()->request->getPost('id');

        $item = Utils::getItemInfo( $item_id );

        $this->renderPartial('item', array('item_id'=>$item_id, 'name'=>$item['name'], 'price'=>$item['gold']['total'], 'description'=>$item['description']));
    }

    public function actionGetSummonerSpell()
    {
        $spell_id = Yii::app()->request->getPost('spell_id');

        $spell = Utils::getSummonerSpell( $spell_id );
        $spell_id = 'passive';
        $spell['tooltip'] = $spell['description'];

        $this->renderPartial('spell', array('spell'=>$spell, 'spell_type'=>$spell_id, 'summoner'=>true));
    }

    public function actionRegister()
    {
        $model = new User();

        if(isset($_POST['ajax'])) {
            if ($_POST['ajax'] == 'registration_form') {
                $model = new User('register');
                $model->attributes = $_POST['User'];
                echo CActiveForm::validate($model);
            }
            Yii::app()->end();
        }

        if( isset($_POST['User']) ){
            $model->attributes = $_POST['User'];
            $password = $model->password;
            $model->role = 'user';
            $model->rank = 1;
            $model->save();

            $identity = new UserIdentity($model->login,$password);
            $identity->authenticate();
            Yii::app()->user->login($identity);

            $this->redirect('/');
        }

        $this->render('register', array('model'=>$model));
    }

    public function actionLeague()
    {
        $current_page = (int)Yii::app()->request->getQuery('page');
        $page_size = 20;
        $db = Yii::app()->db;
        if( Yii::app()->user->isGuest ){
            $user = array('id'=>0, 'rank'=>1);
        } else {
            $user = $db->createCommand()->from('user')->where('id=:id', array('id'=>Yii::app()->user->id))->queryRow();
        }

        $players = $db->createCommand()->from('user')->where('rank=:rank', array('rank'=>$user['rank']))->order('elo DESC')->limit($page_size)->offset(($current_page-1)*$page_size)->queryAll();
        $count = $db->createCommand()->select('COUNT(*) as count')->from('user')->where('rank=:rank', array('rank'=>$user['rank']))->queryScalar();

        $item_count = $count;

        $pages = new CPagination($item_count);
        $pages->setPageSize($page_size);
        // simulate the effect of LIMIT in a sql query
        $end = ($pages->offset + $pages->limit <= $item_count ? $pages->offset + $pages->limit : $item_count);
        $sample = range($pages->offset+1, $end);
        $this->render('league', array(
            'user'=>$user,
            'players'=>$players,
            'item_count'=>$item_count,
            'page_size'=>$page_size,
            'items_count'=>$item_count,
            'pages'=>$pages,
            'sample'=>$sample,
        ));
    }
}