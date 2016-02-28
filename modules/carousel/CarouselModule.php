<?php
namespace yii\easyii\modules\carousel;

class CarouselModule extends \yii\easyii\components\Module
{
    public $settings = [
        'enableTitle' => true,
        'enableText' => true,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Carousel',
            'ru' => 'Карусель',
            'zh' => '跑马灯'
        ],
        'icon' => 'picture',
        'order_num' => 40,
    ];
}