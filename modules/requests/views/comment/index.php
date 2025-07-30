<?php

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $title string */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = $title;
?>
<div class="container">
    <div class="content">
    <?php echo Html::tag('h2', Html::encode($title)); ?>
    </div>
    <?php if ($dataProvider->totalCount > 0): ?>
        <div class="comments-list">
            <?php echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_comment',
                'layout' => "{items}\n{pager}",
            ]);?>
        </div>
    <?php endif; ?>
</div>
