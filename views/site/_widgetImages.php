<?php

use devgroup\dropzone\DropZone;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?=
DropZone::widget(
    [
        'id' => 'Images',
        'name' => 'DocumentForm[file]', // input name or 'model' and 'attribute'
        'url' => Url::toRoute([$url]) , // upload url
        'storedFiles' => $attachments ? $attachments : "", // stores files
        'eventHandlers' => [
            'addedfile' => 'function(file){
                    InitAccept();
            }',
        ], // dropzone event handlers
        'sortable' => true, // sortable flag
        'options' => [
            'dictCancelUpload' => 'Удалить файл',
            'dictRemoveFile' => 'Удалить файл',
            'dictCancelUploadConfirmation' => 'Удалить файл',
            'dictMaxFilesExceeded' => 'Файл не будет загружен, превышен лимит',
            'dictDefaultMessage' => 'Выберите файлы для загрузки',
            'dictInvalidFileType' => 'Не поддерживаемый тип файла',
            'addRemoveLinks' => true,
            'uploadMultiple' => true,
            'autoProcessQueue' => false,
            'parallelUploads' => 50,
            'maxFiles' => 50,
            'acceptedFiles' => 'image/jpg, image/jpeg, image/png',
        ],
    ]
)
?>

<div class="row">
    <div class="col-xs-12">
        <div class="progress">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0"
                 aria-valuemin="0" aria-valuemax="100" style="width:0%">
                0% Complete (success)
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <ul id="errors"></ul>
        <p id="message" class="message"></p>
        <?= Html::hiddenInput('url', Url::to($url, true) , ['id' => 'url']) ?>
        <?= Html::submitButton($isNewRecord ? 'Добавить' : 'Сохранить', [ 'id' => 'send', 'class' => $isNewRecord ? 'btn btn-success add' : 'btn btn-success update']) ?>
    </div>
</div>
