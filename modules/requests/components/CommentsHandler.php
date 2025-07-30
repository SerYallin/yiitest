<?php

namespace app\modules\requests\components;

use app\modules\requests\models\Comments;
use yii\base\Event;
use Throwable;

/**
 * Help handler for Comments model.
 */
class CommentsHandler extends BaseHandler
{
    /**
     *  Delete comments by request event.
     *
     * @param Event $event
     * @return void
     */
    public static function onDeleteRequest(Event $event): void
    {
        $model = $event->sender;
        $comments = Comments::findAll(['requests_id' => $model->id]);
        if ($comments) {
            foreach ($comments as $comment) {
                try {
                    $comment->delete();
                } catch (Throwable $e) {
                    static::addError($e->getMessage());
                }
            }
        }
    }
}
