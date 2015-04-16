<?php
/**
 * @var $form CActiveForm
 * @var $model User
 */
$form = $this->beginWidget('CActiveForm', array(
    'id'=>'registration_form',
    'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
));
?>

<h1 class="blue-text">Registration</h1>

<div class="col l6 s12">
    <div class="row" style="height: 80px">
        <div class="input-field col s6">
            <i class="mdi-action-account-circle prefix blue-text"></i>
            <?php echo $form->textField($model,'login', array('class'=>'blue-text')); ?>
            <?php echo $form->labelEx($model,'login', array('class'=>'blue-text')); ?>
            <?php echo $form->error($model, 'login'); ?>
        </div>
        <div class="input-field col s6">
            <i class="mdi-communication-email prefix blue-text"></i>
            <?php echo $form->textField($model,'email', array('class'=>'blue-text')); ?>
            <?php echo $form->labelEx($model,'email', array('class'=>'blue-text')); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
    </div>
    <div class="row">
        <div class="input-field col s6">
            <i class="prefix blue-text"></i>
            <?php echo $form->passwordField($model,'password', array('class'=>'blue-text')); ?>
            <?php echo $form->labelEx($model,'password', array('class'=>'blue-text')); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>
        <div class="input-field col s6">
            <i class="prefix blue-text"></i>
            <?php echo $form->passwordField($model,'password_repeat', array('class'=>'blue-text')); ?>
            <?php echo $form->labelEx($model,'password_repeat', array('class'=>'blue-text')); ?>
            <?php echo $form->error($model, 'password_repeat'); ?>
        </div>

        <div class="col offset-s7 s5">
            <button class="btn waves-effect waves-light red darken-1" type="submit" name="action">Submit
                <i class="mdi-content-send right white-text"></i>
            </button>
        </div>
    </div>
</div>


<?php
$this->endWidget();
?>