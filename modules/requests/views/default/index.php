<?php
/**
 * @var $this yii\web\View
 *   The view object.
 * @var string $title
 *   The page title.
 * @var ActiveDataProvider $list
 *   The data provider for requests data.
 */

use app\modules\requests\assets\RequestsAssets;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

RequestsAssets::register($this);
?>
    <div class="content">
        <h1><?php echo $title ?></h1>
        <div class="actions">
            <?php echo Html::a(Yii::t('app', 'Создать заявку', [], 'ru-Ru'), Url::to(['default/create']), ['class' => 'btn btn-success', 'data-id' => 'addRequestBtn']); ?>
        </div>
        <?php Pjax::begin(['id' => 'requests-list']); ?>
        <?php echo GridView::widget([
            'dataProvider' => $list,
            'emptyText' => Yii::t('app', 'Нет доступных заявок.', [], 'ru-Ru'),
            'columns' => [
                ['attribute' => 'id', 'enableSorting' => false],
                ['attribute' => 'title', 'enableSorting' => false, 'format' => 'raw', 'content' => function ($model) {
                    return Html::a($model->title, Url::to(['default/view', 'id' => $model->id]));
                }],
                ['attribute' => 'status',
                    'enableSorting' => false,
                    'format' => 'raw',
                    'content' => function ($model) {
                        return Html::dropDownList(
                            'status',
                            $model->status,
                            [
                                0 => 'new',
                                1 => 'in_progress',
                                2 => 'done',
                            ],
                            [
                                'class' => 'form-control',
                                'data-value' => $model->status,
                                'data-action' => Url::to(['default/update-status', 'id' => $model->id])
                            ]
                        );
                    }],
                ['attribute' => 'created_at', 'enableSorting' => false, 'format' => ['date', 'php:Y-m-d H:i']],
                ['attribute' => 'description', 'enableSorting' => false],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return Html::a('', Url::to(['default/delete', 'id' => $model->id]), [
                                'title' => Yii::t('app', 'Удалить', [], 'ru-Ru'),
                                'class' => 'btn-delete',
                                'data-confirm' => Yii::t('app', 'Вы уверены, что хотите удалить заявку?', [], 'ru-Ru')
                            ]);
                        }],
                ],
            ]
        ]); ?>
        <?php Pjax::end(); ?>
    </div>

<?php
Modal::begin([
    'id' => 'modal',
    'title' => 'Добавить заявку',
]);
echo '<div id="modalContent"></div>';
Modal::end();
?>