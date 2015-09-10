<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;
?>
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