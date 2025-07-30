<?php

namespace app\modules\requests\helper;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Helper for working with files.
 */
class RequestFileHelper
{

    /**
     * Save the file by path.
     *
     * @param UploadedFile $file
     *   The file object.
     * @param string $path
     *   The path to save the file.
     * @return string
     *   The path to the saved file.
     *
     * @throws \yii\base\Exception
     *   If the file cannot be saved.
     */
    public static function saveFile(UploadedFile $file, string $path = ''): string
    {
        $dir = Yii::getAlias($path);
        if (!is_dir($dir)) {
            FileHelper::createDirectory($dir, 0777);
        }
        $file_base_name = $file->getBaseName();
        $file_ext = $file->getExtension();
        $filePath = $dir . $file_base_name . '.' . $file_ext;
        if (file_exists($filePath)) {
            $count = 0;
            do {
                $filePath = $dir . $file_base_name . '_' . $count . '.' . $file_ext;
                $count++;
            } while (file_exists($filePath));
        }
        if (!$file->saveAs($filePath)) {
            throw new \Exception('Ошибка сохранения файла: ' . $file->name);
        }
        $webroot = Yii::getAlias('@webroot');
        if (strpos($filePath, $webroot) === 0) {
            $filePath = substr($filePath, strlen($webroot));
        }
        return $filePath;
    }

    /**
     * Remove the file.
     *
     * @param string $path
     *   The path to file.
     * @return bool
     *   True if file was removed.
     */
    public static function removeFile(string $path): bool
    {
        $status = false;
        $dir = Yii::getAlias('@webroot');
        if (strpos($path, $dir) === false) {
            $path = $dir . $path;
        }
        if (file_exists($path)) {
            $status = unlink($path);
        }
        return $status;
    }

}