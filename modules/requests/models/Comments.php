<?php

namespace app\modules\requests\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property int|null $requests_id
 * @property string|null $comment
 * @property string|null $created_at
 */
class Comments extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['requests_id', 'comment'], 'default', 'value' => null],
            [['requests_id'], 'default', 'value' => null],
            [['requests_id', 'comment'], 'required'],
            [['requests_id'], 'integer'],
            [['requests_id'], 'isValidRequest'],
            [['comment'], 'string'],
            [['created_at'], 'safe'],
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
            'comment' => 'Comment',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Validates the request is existed.
     *
     * @param string $attribute
     *   The attribute name.
     * @param array|null $params
     *   The additional parameters.
     * @return void
     */
    public function isValidRequest(string $attribute, array $params = null): void
    {
        if (!Requests::findOne($this->requests_id)) {
            $this->addError($attribute, 'Request with id `' . $this->requests_id . '` not found.');
        }
    }
}
