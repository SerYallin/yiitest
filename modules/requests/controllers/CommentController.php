<?php

namespace app\modules\requests\controllers;

use app\modules\requests\models\Comments;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\db\Exception;
use Yii;

/**
 * Comment controller for the `requests` module
 */
class CommentController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider(['query' => Comments::find(),
            'sort' => ['defaultOrder' => ['created_at' => SORT_ASC]], 'pagination' => ['defaultPageSize' => 20]]);
        return $this->render('index', [
            'title' => Yii::t('app', 'Список комментарий.', [], 'ru-RU'),
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Create comment.
     *
     * @param $id
     *   Id request.
     * @return string
     *   Response form.
     */
    public function actionCreate($id): string
    {
        $comment = new Comments();
        $errors = [];
        $messages = [];
        if ($comment->load(Yii::$app->request->post())) {
            try {
                if (!$comment->save()) {
                    $errors = $comment->getErrors();
                }
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
            $messages[] = Yii::t('app', 'Комментарий успешно добавлен', [], 'ru-RU');
        } else {
            $errors = $comment->getErrors();
            if (empty($errors)) {
                $errors[] = Yii::t('app', 'Ошибка добавления комментария', [], 'ru-RU');
            }
        }
        if (Yii::$app->request->isAjax || Yii::$app->request->isPjax) {
            return $this->renderAjax('_form', ['errors' => $errors, 'comment' => $comment, 'messages' => $messages, 'id' => $id]);
        }
        return $this->render('_form', ['errors' => $errors, 'comment' => $comment, 'messages' => $messages, 'id' => $id]);
    }
}
