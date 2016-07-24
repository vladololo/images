<?php

namespace app\controllers;

use app\models\Attachment;
use app\models\DocumentForm;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\Response;
use app\models\Document;
use app\models\DocumentSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Document models.
     * @return mixed
     */
    public function actionDocumentList()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('documentList', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Document();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        foreach ($model->attachment as $item) {
            unlink(Yii::$app->basePath."/web/upload/".$item["thumbnail"]);
        };

        $model->delete();

        $this->redirect(Url::to("document-list"));
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::find()->with('attachment')->where(['Id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionIndex()
    {
        $modelDocumentForm = new DocumentForm();
        return $this->render('index', ['modelDocumentForm' => $modelDocumentForm]);
    }

    public function actionUpdateDocument($id)
    {
        $modelDocumentForm = new DocumentForm(['scenario' => 'update']);
        $modelDocumentForm->Images = UploadedFile::getInstancesByName('DocumentForm[file]');  // Получаем картинки из поста
        $modelDocumentForm->Sort = Json::decode(Yii::$app->request->post()['Sort']);
        $modelDocumentForm->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON; // указываем что ответ будет в json формате

        if ($modelDocumentForm->validate()) {    // Валидируем данные
            $response = $modelDocumentForm->Update($id);
            if($response === true)
                return ["success" => 'Ваш документ успешно обновлен'];
            elseif ($response === false)
                return ["error" => 'К сожалению произошла ошибка при обновлении, повторите попытку'];
            else return ["message" => $response];
        }else return  $modelDocumentForm->getErrors();
    }

    public function actionAddDocument()
    {
        $modelDocumentForm = new DocumentForm();
        $modelDocumentForm->Images = UploadedFile::getInstancesByName('DocumentForm[file]');  // Получаем картинки из поста
        $modelDocumentForm->Sort = Json::decode(Yii::$app->request->post()['Sort']);
        $modelDocumentForm->load(Yii::$app->request->post());
        Yii::$app->response->format = Response::FORMAT_JSON; // указываем что ответ будет в json формате

        if ($modelDocumentForm->validate()) {    // Валидируем данные
            $response = $modelDocumentForm->Add();
            if($response === true)
                return ["success" => 'Ваш документ успешно добавлен'];
            elseif ($response === false)
                return ["error" => 'К сожалению произошла ошибка при добавлении, повторите попытку'];
            else return ["message" => $response];
        }else return  $modelDocumentForm->getErrors();
    }
}
