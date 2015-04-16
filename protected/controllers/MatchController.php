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
		$this->render('index');
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