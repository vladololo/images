<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Attachment".
 *
 * @property integer $Id
 * @property string $thumbnail
 * @property integer $Size
 * @property string $Name
 * @property integer $IdDocument
 * @property integer $Position
 *
 * @property Document $idDocument
 */
class Attachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['thumbnail', 'size', 'name', 'IdDocument', 'Position'], 'required'],
            [['size', 'IdDocument', 'Position'], 'integer'],
            [['thumbnail', 'name'], 'string', 'max' => 255],
            [['IdDocument'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['IdDocument' => 'Id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'thumbnail' => 'thumbnail',
            'size' => 'Size',
            'name' => 'Name',
            'IdDocument' => 'Id Document',
            'Position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDocument()
    {
        return $this->hasOne(Document::className(), ['Id' => 'IdDocument']);
    }
}