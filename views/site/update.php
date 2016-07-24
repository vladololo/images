<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use devgroup\dropzone\DropZone;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Document */

$this->title = 'Обновление документа: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="document-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['enableClientValidation' => false ]); ?>

    <?= $form->field($model, 'Name')->textInput(['maxlength' => true, 'id' => 'Name']) ?>

    <?= $form->field($model, 'Description')->textarea(['rows' => 6, 'id' => 'Description']) ?>

    <?php ActiveForm::end(); ?>

    <?= $this->render('_widgetImages', ['url' => Url::toRoute(['site/update-document', 'id' => Yii::$app->request->get('id')], false), 'attachments' => $model->attachment, 'isNewRecord' => false]) ?>


</div>
