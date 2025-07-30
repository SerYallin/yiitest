<?php
namespace app\modules\requests\assets;

use yii\validators\ValidationAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\widgets\ActiveFormAsset;
use yii\widgets\PjaxAsset;

class RequestsAssets extends AssetBundle
{
    public $sourcePath = '@requests';
    public $baseUrl = '@web';
    public $js = [
        'assets/js/requests.js',
    ];
    public $depends = [
        JqueryAsset::class,
        ActiveFormAsset::class,
        ValidationAsset::class,
        PjaxAsset::class,
    ];
    public $css = [
        'assets/css/requests.css',
    ];

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        $this->publishOptions['forceCopy'] = true;
        parent::init();
    }
}
