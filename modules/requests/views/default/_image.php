<?php
/**
 * @var $model \app\modules\requests\models\Images
 * @var int $request_id;
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="image-item">
    <?= Html::img(Url::to($model->url), ['alt' => $model->alt]); ?>
    <?= Html::a('', Url::to(['default/delete-image', 'id' => $request_id, 'image_id' => $model->id]), [
        'title' => Yii::t('app', 'Удалить', [], 'ru-Ru'),
        'class' => 'btn-delete',
        'data-confirm' => Yii::t('app', 'Вы уверены, что хотите удалить заявку?', [], 'ru-Ru')
    ]) ?>
</div>
