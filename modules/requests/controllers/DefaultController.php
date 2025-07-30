<?php

namespace app\modules\requests\controllers;

use app\modules\requests\models\Comments;
use app\modules\requests\models\Images;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\Controller;
use app\modules\requests\models\Requests;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Default controller for the `requests` module
 */
class DefaultController extends Controller
{

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider(['query' => Requests::find(),
            'sort' => ['defaultOrder' => ['created_at' => SORT_ASC]], 'pagination' => ['defaultPageSize' => 20]]);
        return $this->render('index', [
            'title' => \Yii::t('app', 'Список Заявок.', [], 'ru-RU'),
            'list' => $dataProvider
        ]);
    }

    /**
     * Action for view request on single page.
     *
     * @param int $id
     *   Request id.
     * @return string
     */
    public function actionView(int $id): string
    {
        $newComment = new Comments();
        $model = Requests::findOne($id);
        $commentsProvider = new ActiveDataProvider(['query' => Comments::find()->where(['requests_id' => (int)$id]),
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]], 'pagination' => ['defaultPageSize' => 20]]);

        $imagesProvider = new ActiveDataProvider(['query' => Images::find()->where(['id' => $model->images ?? []]), 'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]]);

        return $this->render('view', [
            'model' => $model,
            'comment_form' => $this->renderAjax('/comment/_form', ['comment' => $newComment, 'id' => $id, 'errors' => [], 'messages' => []]),
            'comments' => $commentsProvider,
            'images_form' => $this->renderAjax('_image_form', ['model' => $model, 'id' => $id, 'errors' => [], 'messages' => []]),
            'images' => $imagesProvider,
        ]);
    }

    /**
     * Action for create request.
     *
     * @return string|Response
     *   Rendered form view.
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $model = new Requests();
        if ($model->load(Yii::$app->request->post())) {
            $response = \Yii::$app->getResponse();
            $response->format = Response::FORMAT_JSON;
            if ($model->save()) {
                $response->data = ['status' => 'success', 'message' => Yii::t('app', 'Заявка успешно создана', [], 'ru-RU')];
            } else {
                $response->data = ['status' => 'error', 'errors' => $model->getErrors()];
            }
            return $response;
        }
        if (Yii::$app->request->isAjax || Yii::$app->request->isPjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Action for update request status.
     *
     * @param int $id
     *   Request id.
     * @return Response|\yii\console\Response
     */
    public function actionUpdateStatus(int $id): Response|\yii\console\Response
    {
        $response = \Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;

        $status = Yii::$app->request->post('status');
        $model = new Requests();
        if ($model->updateStatus($id, $status)) {
            $response->data = ['status' => 'success', 'message' => Yii::t('app', 'Статус изменен', [], 'ru-RU')];
        } else {
            $response->data = ['status' => 'error', 'errors' => $model->getErrors()];
        }

        return $response;
    }

    /**
     * Action for delete request.
     *
     * @param int $id
     *   Request id.
     * @return Response|\yii\console\Response
     * @throws \Throwable
     */
    public function actionDelete(int $id): Response|\yii\console\Response
    {
        $response = \Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;
        $model = Requests::findOne($id);
        if ($model) {
            try {
                $model->delete();
                $response->data = ['status' => 'success', 'message' => Yii::t('app', 'Заявка удалена', [], 'ru-RU')];
            } catch (Exception $e) {
                $response->statusCode = 500;
                $response->data = ['status' => 'error', 'errors' => $e->getMessage()];
            }
        } else {
            $response->statusCode = 404;
            $response->data = ['status' => 'error', 'errors' => Yii::t('app', 'Заявка не найдена', [], 'ru-RU')];
        }
        return $response;
    }

    /**
     * Action for add image.
     *
     * @param int $id
     *   Request id.
     * @return string
     *   Rendered form view for upload image.
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionAddImage(int $id): string
    {
        $messages = [];
        $errors = [];
        $model = Requests::findOne($id);
        if (!$model) {
            $errors[] = YII::t('app', 'Заявка не найдена', [], 'ru-RU');
        }
        $uploadedFiles = UploadedFile::getInstances($model, 'files');
        if ($uploadedFiles) {
            $images = YII::$app->ImagesHandler::loadImages($uploadedFiles, $id);
            $errors = array_merge($errors, YII::$app->ImagesHandler::getErrors());
            $oldImages = $model->getAttribute('images');
            $images = array_merge($images, $oldImages?->getValue() ?? []);
            $model->setAttribute('images', $images);
            $model->update(true, ['images']);
        } else {
            $errors[] = YII::t('app', 'Нужно загрузить хотя бы одну картинку.', [], 'ru-RU');
        }
        $errors = array_merge($errors, $model->getErrors());
        if (\Yii::$app->request->isAjax || \Yii::$app->request->isPjax) {
            return $this->renderAjax('_image_form', [
                'model' => $model,
                'messages' => $messages,
                'errors' => $errors,
                'id' => $id,
            ]);
        }
        return $this->render('_image_form', [
            'model' => $model,
            'messages' => $messages,
            'errors' => $errors,
            'id' => $id,
        ]);
    }

    /**
     * Action for delete image.
     *
     * @param int $id
     *   Request id.
     * @param int $image_id
     *   Image id.
     * @return Response
     *   Output response.
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteImage(int $id, int $image_id): Response
    {
        $response = \Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;
        $response->data = [];
        $errors = [];
        $messages = [];
        $model = Requests::findOne($id);
        if (!$model) {
            $errors[] = YII::t('app', 'Заявка не найдена', [], 'ru-RU');
        }
        $oldImages = $model->getAttribute('images');
        $images_ids = $oldImages?->getValue() ?? [];
        if (empty($errors) && in_array($image_id, $images_ids)) {
            YII::$app->ImagesHandler::deleteImage($image_id);
            $errors = YII::$app->ImagesHandler::getErrors();
            if (empty($errors)) {
                $images_ids = array_diff($images_ids, [$image_id]);
                $model->setAttribute('images', $images_ids);
                $model->update(true, ['images']);
                $errors = $model->getErrors();
            }
        }
        if (empty($errors)) {
            $messages[] = Yii::t('app', 'Изображение удалено', [], 'ru-RU');
            $response->data = ['status' => 'success', 'messages' => $messages];
        } else {
            $response->statusCode = 500;
            $response->data = ['status' => 'error', 'errors' => $errors];
        }
        return $response;
    }
}
