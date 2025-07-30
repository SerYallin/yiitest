<?php
/**
 * @file The add comment from presenter.
 */

/* @var $this View */
/* @var $messages array */
/* @var $comment Comments */
/* @var $errors array|string[][] */
/* @var $model \app\modules\requests\models\Comments */
/* @var $id  */

use app\modules\requests\models\Comments;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>
<?php PJax::begin([
    'id' => 'add-comment-form',
    'enablePushState' => false,
]); ?>
<?php
$form = ActiveForm::begin([
    'action' => Url::to(['comment/create', 'id' => $id]),
    'options' => ['data-pjax' => true],
    'id' => 'comment-form',
]); ?>
<?= $form->field($comment, 'comment')
    ->textarea(['rows' => 4, 'placeholder' => 'Прокомментируйте...', 'value' => ''])
    ->label('Введите комментарий') ?>
<?= $form->field($comment, 'requests_id')->hiddenInput(['value' => $id])->label(false) ?>
<?php if ($messages): ?>
    <?php foreach ($messages as $message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endforeach; ?>
<?php endif; ?>
<?php if ($errors): ?>
    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="form-group">
    <?= Html::submitButton('Отправить комментарий', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
<?php PJax::end(); ?>
