<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Document".
 *
 * @property integer $Id
 * @property string $Name
 * @property string $Description
 *
 * @property Attachment[] $attachments
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'Description'], 'required'],
            [['Description'], 'string'],
            [['Name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Название',
            'Description' => 'Описание',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['IdDocument' => 'Id']);
    }

    public function getAttachment()
    {
        return $this->hasMany(Attachment::className(), ['IdDocument' => 'Id'])->orderBy(['Position' => SORT_ASC]);
    }
}
