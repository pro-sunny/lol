<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
    <meta name="theme-color" content="#2196F3">

	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/materialize.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome.min.css" />


    <!--  Scripts-->
    <?
    $baseUrl = $this->assetsBase;
    $cs = Yii::app()->getClientScript();
    $cs->registerCoreScript('jquery.ui');
    // $cs->registerScriptFile($baseUrl.'/js/main.js');
    $cs->registerScriptFile($baseUrl.'/js/modernizr.js');
    $cs->registerScriptFile($baseUrl.'/js/materialize.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($baseUrl.'/js/init.js', CClientScript::POS_END);
    ?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body id="top" class="scrollspy">

<!-- Pre Loader -->
<div id="loader-wrapper">
    <div id="loader"></div>

    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>

</div>

<!--Navigation-->
<div class="navbar-fixed">
    <nav id="nav_f" class="default_color" role="navigation">
        <div class="container">
            <div class="nav-wrapper">
                <a id="logo-container" href="#top" class="brand-logo">URF League</a>
                <ul id="nav-mobile" class="right side-nav">
                    <?php $this->widget('zii.widgets.CMenu',array(
                        'items'=>array(
                            array('label'=>'Home', 'url'=>array('/site/index')),
                            array('label'=>'League', 'url'=>array('/site/league')),
//                            array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
//                            array('label'=>'Contact', 'url'=>array('/site/contact')),
                            array('label'=>'Registration', 'url'=>array('/site/register')),
                            array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                            array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                        ),
                    )); ?>
                </ul><a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
            </div>
        </div>
    </nav>
</div>

<? if( $this->id != 'match' ){ ?>
<div class="parallax-container">
    <div class="section no-pad-bot">
        <div class="container">
            <br><br>
            <h1 class="header center text_b">Wellcome to the URF League!</h1>
            <div class="row center">
                <h5 class="header col s12 light">Prepare yourself for an ultimate battle!!!</h5>
            </div>
            <div class="row center">
                <? if( Yii::app()->user->isGuest ){
                    echo CHtml::link('Log In', '/site/login', array('class'=>'btn-large waves-effect waves-light light-blue accent-3'));
                } else {
                    echo CHtml::link('Fight', '/match', array('class'=>'btn-large waves-effect waves-light light-blue accent-3'));
                }
                ?>
            </div>
            <br><br>

        </div>
    </div>
    <div class="parallax"><img src="<?= Yii::app()->params['webRoot'].'/images/urf_logo.jpg';?>"></div>
</div>
<? } ?>

<? if( $this->id == 'site' && $this->action->id == 'index' ){ ?>
    <h2 class="center header text_h2"> Answer simple questions to become URF challenger! </h2>
    <h4 class="center text_h2">The time for random clicking is over! It is now you have to show all your best or no one will ever notice you.
        Think twice(or even better 3 times) before making your decision, because it will change everything. </h4>

    <div class="parallax-container">
        <div class="parallax"><img src="<?= Yii::app()->params['webRoot'].'/images/screen2.jpg';?>"></div>
    </div>


    <h4 class="center text_h2">
        But be prepared, as long it goes, as harder it gets. <br>
        Not only by competitor but the system will add more and more complication.<br>
        You think you can handle it?
    </h4>
    <div class="center">
        <? if( Yii::app()->user->isGuest ){
            echo CHtml::link('Log In', '/site/login', array('class'=>'btn-large waves-effect waves-light light-blue accent-3'));
        } else {
            echo CHtml::link('Fight', '/match', array('class'=>'btn-large waves-effect waves-light light-blue accent-3'));
        }
        ?>
    </div>
    <br>
    <br>
<? } else { ?>
    <div class="section scrollspy" id="team">
        <div class="container" style="text-align: center">
            <?php echo $content; ?>
        </div>
    </div>
<? }?>


<!--Footer-->
<footer id="contact" class="page-footer default_color scrollspy">
    <div class="footer-copyright default_color">
        <div class="container">
            Inspired By <a class="white-text" href="https://developer.riotgames.com/discussion/riot-games-api/show/bX8Z86bm">RIOT API Challenge</a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Developed By <a class="white-text" href="https://github.com/pro-sunny">ProSunny</a>
        </div>
    </div>
</footer>

</body>
</html>
