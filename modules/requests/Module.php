<?php

namespace app\modules\requests;

use app\modules\requests\components\CommentsHandler;
use app\modules\requests\components\ImagesHandler;
use app\modules\requests\models\Requests;
use yii\base\Event;
use yii\base\InvalidConfigException;
use Yii;

/**
 * requests module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\requests\controllers';

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        Yii::setAlias('@requests', __DIR__);
        $config = require(\Yii::getAlias('@requests/config/main.php'));
        YII::$app->setComponents($config['components']);

        $this->initEvents();
    }

    /**
     * Subscribe to global events.
     *
     * @return void
     */
    private function initEvents(): void
    {
        Event::on(Requests::class, Requests::EVENT_AFTER_DELETE, [ImagesHandler::class, 'onDeleteRequest']);
        Event::on(Requests::class, Requests::EVENT_AFTER_DELETE, [CommentsHandler::class, 'onDeleteRequest']);
    }
}
