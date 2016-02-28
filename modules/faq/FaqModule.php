<?php
namespace yii\easyii\modules\faq;

use Yii;

class FaqModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'FAQ',
            'ru' => 'Вопросы и ответы',
            'zh-hans' => 'FAQ',
        ],
        'icon' => 'question-sign',
        'order_num' => 45,
    ];
}