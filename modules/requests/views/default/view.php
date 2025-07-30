<?php

/* @var $this \yii\web\View */
/* @var $model \app\modules\requests\models\Requests|null */
/* @var $comment_form string */
/* @var $comments \yii\data\ActiveDataProvider */
/* @var $images_form string */
/* @var $images \yii\data\ActiveDataProvider */

use app\modules\requests\assets\RequestAssets;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

RequestAssets::register($this);
$this->title = $model->title;
// This should be if the url for requests is different from home.
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <h2><?php echo Html::encode($model->title); ?></h2>
    <?php if (!empty($model->description)): ?>
        <div class="description">
            <p><?php echo nl2br(Html::encode($model->description)); ?></p>
        </div>
    <?php endif; ?>
    <?php Pjax::begin(['id' => 'images-list']); ?>
    <?php if ($images->totalCount > 0): ?>
        <?php echo ListView::widget([
            'dataProvider' => $images,
            'itemView' => '_image',
            'viewParams' => [
                'request_id' => $model->id,
            ],
        ]);?>
    <?php endif; ?>
    <?php Pjax::end(); ?>
    <?= $images_form ?>
    <?= $comment_form ?>
    <?php Pjax::begin(['id' => 'comments-list']); ?>
    <?php if ($comments->totalCount > 0): ?>
        <div class="comments-list">
            <?php echo ListView::widget([
                'dataProvider' => $comments,
                'itemView' => '../comment/_comment',
                'layout' => "{items}\n{pager}",
            ]);?>
        </div>
    <?php endif; ?>
    <?php Pjax::end(); ?>
</div>