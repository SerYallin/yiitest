<?php

use app\modules\requests\assets\RequestAssets;
use app\modules\requests\models\Comments;
use yii\helpers\Html;

/**
 * @var Comments $model
 */

RequestAssets::register($this);
?>
<div class="comment-item">
    <p class="comment-date"><?= Html::encode(Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i')); ?></p>
    <p class="comment-text"><?= Html::encode($model->comment); ?></p>
</div>
