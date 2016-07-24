<?php

/* @var $this yii\web\View */
use devgroup\dropzone\DropZone;
use yii\widgets\ActiveForm;

$this->title = 'Добавить документ';


?>

<?php $form = ActiveForm::begin(['enableClientValidation' => false ]); ?>
    <?= $form->field($modelDocumentForm, 'Name')->textInput(['id' => 'Name']) ?>
    <?= $form->field($modelDocumentForm, 'Description')->textarea(['id' => 'Description']) ?>
<?php ActiveForm::end(); ?>

<?= $this->render('_widgetImages', ['url' => 'site/add-document', 'isNewRecord' => true])?>




