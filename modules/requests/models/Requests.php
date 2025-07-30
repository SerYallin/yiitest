<?php

namespace app\modules\requests\models;

use Yii;
use yii\db\ActiveRecord;
use yii\validators\InlineValidator;

/**
 * This is the model class for table "requests".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Requests extends ActiveRecord
{
    /**
     * @var array
     *   Array of uploaded files.
     */
    public $files;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxFiles' => 5, 'maxSize' => 2097152],
            [['title'], 'required'],
            [['title', 'description'], 'default', 'value' => null],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['images'], 'each', 'rule' => ['integer'], 'skipOnEmpty' => true],
            ['images', 'validateImagesCount'],
            [['status'], 'default', 'value' => 0],
            [['status'], 'number', 'min' => 0, 'max' => 2],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID', [], 'en-En'),
            'title' => Yii::t('app', 'Заголовок', [], 'ru-Ru'),
            'description' => Yii::t('app', 'Действия', [], 'ru-Ru'),
            'status' => Yii::t('app', 'Статус', [], 'ru-Ru'),
            'images' => Yii::t('app', 'Картинки', [], 'ru-Ru'),
            'created_at' => Yii::t('app', 'Дата создания', [], 'ru-Ru'),
            'updated_at' => Yii::t('app', 'Дата обновления', [], 'ru-Ru'),
        ];
    }

    /**
     * Update status method.
     *
     * @param int $id
     *   Record ID.
     * @param int $new_status
     *   New status number
     * @return bool
     *   Determines whether the operation was successful.
     * @throws \yii\db\Exception
     */
    public function updateStatus($id, $new_status): bool
    {
        $status = true;
        if ($record = static::findOne($id)) {
            $record->status = $new_status;
            if (!$record->save()) {
                $status = false;
                Yii::error($record->getErrors());
                $this->addError('status', $record->getErrors());
            }
        }
        return $status;
    }

    /**
     * Validate images count for request.
     *
     * @param string $attribute
     *   Attribute name.
     * @param $params
     *   The parameters to be validated against.
     * @param InlineValidator $validator
     *   Validator.
     * @return void
     */
    public function validateImagesCount($attribute, $params, $validator)
    {
        if (is_array($this->$attribute) && count($this->$attribute) > 5) {
            $error = Yii::t('app', 'Количество изображений не должно превышать 5.', [], 'ru-Ru');
            $this->addError($attribute, $error);
        }
    }
}
