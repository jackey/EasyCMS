<?php
namespace yii\easyii\modules\file;

class FileModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Files',
            'ru' => 'Файлы',
            'zh-hans' => '文件',
        ],
        'icon' => 'floppy-disk',
        'order_num' => 30,
    ];
}