<?php
/* @var $this \yii\web\View */
/* @var $id int */
/* @var $messages array */
/* @var $errors array */
/* @var $model \app\modules\requests\models\Requests */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>
<?php PJax::begin([
    'id' => 'upload-images-form',
    'enablePushState' => false,
]); ?>
<?php $form = ActiveForm::begin([
    'id' => 'images-upload-form',
    'action' => Url::to(['default/add-image', 'id' => $id]),
    'enableClientValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true],
]) ?>
<?= $form->field($model, 'files[]')->fileInput([
    'multiple' => true,
    'maxFileCount' => 2,
    'required' => true,
    'accept' => 'image/png, image/jpeg',
])->label('Загрузить изображения'); ?>
<?php if ($messages): ?>
    <?php foreach ($messages as $message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endforeach; ?>
<?php endif; ?>
<?php if ($errors): ?>
    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?= is_array($error) ? implode(': ', $error) : $error ?></div>
    <?php endforeach; ?>
<?php endif; ?>
<div class="form-group">
    <?php echo Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
</div>
<div class="files-preview"></div>
<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>

