<?php

namespace app\modules\requests\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "images".
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $alt
 * @property string|null $created_at
 */
class Images extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'alt'], 'default', 'value' => null],
            [['url'], 'required'],
            [['created_at'], 'safe'],
            [['url', 'alt'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'requests_id' => 'Requests ID',
            'url' => 'Url',
            'alt' => 'Alt',
            'created_at' => 'Created At',
        ];
    }
}
