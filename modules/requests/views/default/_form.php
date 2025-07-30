<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\requests\models\Requests $model */
/** @var ActiveForm $form */
?>
<div class="create_form">

    <?php $form = ActiveForm::begin([
        'id' => 'create-form',
        'options' => [
            'method' => 'post',
        ],
        'action' => Url::to(['default/create']),
    ]); ?>

    <?php echo $form->field($model, 'title')
        ->label(Yii::t('app', 'Заголовок', [], 'ru-Ru')); ?>
    <?php echo $form->field($model, 'description')
        ->textarea(['rows' => 6])
        ->label(Yii::t('app', 'Описание', [], 'ru-Ru')); ?>
    <div class="form-group">
        <?php echo Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- create_form -->
