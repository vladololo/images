<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 20.07.2016
 * Time: 12:40
 */

namespace app\models;

use yii\base\ErrorException;
use yii\base\Model;

class DocumentForm extends Model
{
    public $Name;
    public $Description;
    public $Images;
    public $Sort;
    private $arrSaveImages = []; // Создаем массив для хранения данных о местоположении, размере и сортировке картинки.

    public function rules()
    {
        return [
            [['Name', 'Description'], 'required'],
            [['Sort'], 'required', 'message' => 'Выберите хотя бы один файл'],
            [['Description'], 'string'],
            [['Name'], 'string', 'max' => 255],
            [['Images'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 50]
        ];
    }

    public function attributeLabels()
    {
        return [
            'Name' => 'Название',
            'Description' => 'Описание',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['Name', 'Description', 'Sort'];
        return $scenarios;
    }

    // Сортировка и удаление картинок из базы и папки
    private function sortAndDelete($IdDocument)
    {
        $arrUpdateImages = [];

        // Применяем сортировку
        foreach ($this->Sort as $key => $value) {
            $model = Attachment::find()->where(':url like CONCAT("%", `thumbnail`)', [':url' => $value['src']])->one();

            if ($model !== null) {
                $model->Position = $key;
                $model->save();
                array_push($arrUpdateImages, $model->Id);
            }
        }

        // Ещем картинки которые были удалены
        $modelFind = Attachment::find()->where(['IdDocument' => $IdDocument])->andWhere(['NOT IN', 'Id', $arrUpdateImages])->all();

        // Проверяем есть ли у нас файлы для удаления, то удаляем их с базы и с папки
        if (count($modelFind) != 0) {
            foreach ($modelFind as $value) {
                $value->delete();
                unlink(\Yii::$app->basePath . "/web/upload/" . $value->thumbnail);
            }
        }
    }

    // Добавление данных о картинке в базу
    private function InsertBaseImages($IdDocument)
    {
        foreach ($this->Images as $key => $file) // Проходимся по всем картинкам
        {
            $attachment = new Attachment();
            $attachment->thumbnail = $this->arrSaveImages["InsertBD"]['Name'][$key]; // Вносим название картинки
            $attachment->name = $file->baseName; // Вносим оригинал названия
            $attachment->size = $this->arrSaveImages["InsertBD"]['Size'][$key]; // Вносим размер файла
            $attachment->Position = $this->arrSaveImages["InsertBD"]['Position'][$key];
            $attachment->IdDocument = $IdDocument;    // Айди документа
            if (!$attachment->save())    // Сохраняем данные
                throw new ErrorException("Ошибка добавления картинки c именем " . $file->baseName . ". Повторите попытку."); // Если хотя бы одна картинка не добавлилась, создаем исключение
        }
    }

    // Добавление документа в базу, возвращаем айдишник
    private function InsertDataDocument($document)
    {
        // Вносим данные
        $document->Name = $this->Name;
        $document->Description = $this->Description;

        if (!$document->Save()) // Сохраняем, если докумени был добавлен, то добавляем и картинки
            throw new ErrorException("Ошибка добавления документа"); // Если документ не добавлся то выводим искючение

        return $document->Id;
    }

    // Метод обновления документа
    public function Update($IdDocument)
    {
        try {
            $this->upload();   // Сохраняем картинки и получаем данные о местоположении
        } catch (ErrorException $e) {
            $this->rollBackUpload($this->arrSaveImages);  // Делаем откат картинок которые сохраняли (удаляем их)
            return $e->getMessage();   // возваращаеи сообщение, если что-то пошло не так
        }

        // Получаем документ по айдихе
        $document = Document::findOne($IdDocument);

        $transaction = Document::getDb()->beginTransaction(); // Открываем транзакцию в модели документ
        try {

            // Добавляем данные в документ, получаем айди
            $this->InsertDataDocument($document);

            // Сортируем и удаляем существующие файлы
            $this->sortAndDelete($IdDocument);

            // Вставляем картинки в базу
            $this->InsertBaseImages($IdDocument);

            $transaction->commit(); // Выполяем все операции
        } catch (Exception $e) {    // Ловам исключения класса Exception
            $this->rollBackUpload();  // Делаем откат картинок которые сохраняли (удаляем их)
            $transaction->rollback();   // Откат всех действий которые внесли в БД
            return false;   // возваращаем сообщение, если что-то пошло не так
        } catch (ErrorException $e)  // Ловим исключения класса ErrorException
        {
            $this->rollBackUpload();  // Делаем откат картинок которые сохраняли (удаляем их)
            $transaction->rollback(); // Откат всех действий которые внесли в БД
            return $e->getMessage();   // возваращаеи сообщение, если что-то пошло не так
        }
        return true;    // возваращаеи правду, если всё прошло хорошо

    }

    // Метод добавления документа
    public function Add()
    {
        try {
            $this->upload();   // Сохраняем картинки и получаем данные о местоположении
        } catch (ErrorException $e) {
            $this->rollBackUpload($this->arrSaveImages);  // Делаем откат картинок которые сохраняли (удаляем их)
            return $e->getMessage();   // возваращаеи сообщение, если что-то пошло не так
        }

        // Создаем документ
        $document = new Document();

        $transaction = Document::getDb()->beginTransaction(); // Открываем транзакцию в модели документ
        try {

            // Добавляем данные в документ, получаем айди
            $idDocument = $this->InsertDataDocument($document);

            // Вставляем картинки в базу
            $this->InsertBaseImages($idDocument);

            $transaction->commit(); // Выполяем все операции
        } catch (Exception $e) {    // Ловам исключения класса Exception
            $this->rollBackUpload();  // Делаем откат картинок которые сохраняли (удаляем их)
            $transaction->rollback();   // Откат всех действий которые внесли в БД
            return false;   // возваращаем сообщение, если что-то пошло не так
        } catch (ErrorException $e)  // Ловим исключения класса ErrorException
        {
            $this->rollBackUpload();  // Делаем откат картинок которые сохраняли (удаляем их)
            $transaction->rollback(); // Откат всех действий которые внесли в БД
            return $e->getMessage();   // возваращаеи сообщение, если что-то пошло не так
        }
        return true;    // возваращаеи правду, если всё прошло хорошо
    }

    // Метод отката картинок, получаем на вход массив данных о местоположении
    private function rollBackUpload()
    {
        foreach ($this->Images as $key => $file) {  // Прокручиваем все пути
            unlink($this->arrSaveImages["unlinkImg"][$key]);   // Удаляем картинки
        }
    }

    // Метод сохранения картинок
    private function upload()
    {
        foreach ($this->Images as $key => $file) {  // Прокручиваем полученый картинки
            $ImgUnique = uniqid(time());    // Создаем уникальное имя на основании времени
            $this->arrSaveImages["InsertBD"]['Position'][$key] = $this->getSortPosition($file->baseName . '.' . $file->extension);   // Получаем позицию сортировки.
            $pathImg = \Yii::getAlias('@webroot/upload/' . $ImgUnique . '.' . $file->extension);    // Получаем путь сохранения
            $this->arrSaveImages["unlinkImg"][$key] = $pathImg;    // Вносим в массив путь удаления картинки
            $this->arrSaveImages["InsertBD"]['Size'][$key] = $file->size;    // Вносим в массив путь удаления картинки
            $this->arrSaveImages["InsertBD"]['Name'][$key] = $ImgUnique . '.' . $file->extension;  // Вносим название картинки для базы, так как путей не должно быть в базе.
            $file->saveAs($pathImg);    // Сохраняем картинки
        }
    }

    // Получаем название картинки с расширением и возвращаем позицию.
    private function getSortPosition($fileName)
    {
        foreach ($this->Sort as $key => $value) {
            if ($value['alt'] == $fileName) {
                unset($this->Sort[$key]);
                return $key;
            }
        }

        throw new ErrorException("Ошибка сортировки, повторите попытку");
    }

}