<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Документы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'Name',
            [
                'attribute' => 'Description',
                'content' => function ($data) {
                    return StringHelper::truncate($data->Description,200,'...');
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        $url = Url::toRoute(['site/update', 'id' => $model->Id]);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                    },
                    'delete' => function ($url, $model) {
                        $url = Url::toRoute(['site/delete', 'id' => $model->Id]);
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
                            ['data' => [
                                'confirm' => 'Вы действительно желаете удалить документ?',
                                'method' => 'post',
                            ]]
                        );
                    },

                ],
            ],
        ],
    ]); ?>
</div>
