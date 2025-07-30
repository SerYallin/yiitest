<?php

namespace app\modules\requests\components;

use app\modules\requests\helper\RequestFileHelper;
use app\modules\requests\models\Images;
use Yii;
use yii\base\Event;
use Throwable;
use yii\web\UploadedFile;

/**
 * Help handler for images model.
 */
class ImagesHandler extends BaseHandler
{
    /**
     *  Load image files to the server.
     *
     * @param UploadedFile[] $files
     *     Array of UploadedFile
     * @param int $id
     *     Request id
     * @return array
     *     Array of Image ids.
     */
    public static function loadImages(array $files, int $id): array
    {
        $loadedImages = [];

        foreach ($files as $file) {
            $path = Yii::getAlias('@webroot') . '/uploads/request/' . $id . '/';
            try {
                $filePath = RequestFileHelper::saveFile($file, $path);
                $loadedImages[] = static::addImage($filePath);
            } catch (Throwable $e) {
                static::addError($e->getMessage());
            }
        }
        return $loadedImages;
    }

    /**
     *  Delete image from the server.
     *
     * @param int $id
     *     Image id.
     * @return void
     */
    public static function deleteImage(int $id): void
    {
        $model = Images::findOne($id);
        if (!$model) {
            static::addError(Yii::t('app', 'Картинка не найдена', [], 'ru-RU'));
        } else {
            RequestFileHelper::removeFile($model->url);
            try {
                if (!$model->delete()) {
                    static::addError(Yii::t('app', 'Картинка не удалена из базы', [], 'ru-RU'));
                }
            } catch (Throwable $e) {
                static::addError($e->getMessage());
            }
        }
    }

    /**
     *  Save image to the database by path.
     * @param $path
     *     Path to image
     * @return int
     *     Image id
     * @throws Throwable
     */
    public static function addImage($path): int
    {
        $model = new Images();
        $model->url = $path;
        $info = explode('.', basename($path), 2);
        $model->alt = $info[0];
        if (!$model->save()) {
            static::addError(Yii::t('app', 'Ошибка при сохранении изображения', [], 'ru-RU'));
        }
        return $model->id;
    }

    /**
     *  Delete images by request event.
     *
     * @param Event $event
     *     Event object.
     * @return void
     */
    public static function onDeleteRequest(Event $event): void
    {
        $model = $event->sender;
        $images = $model->getAttribute('images');
        if ($images) {
            foreach ($images as $image) {
                static::deleteImage($image);
            }
        }
    }
}
