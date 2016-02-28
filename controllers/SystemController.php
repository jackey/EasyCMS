<?php
namespace yii\easyii\controllers;

use Yii;
use yii\easyii\helpers\WebConsole;
use yii\easyii\models\Setting;
use yii\easyii\helpers\Data;
use yii\easyii\models\Module;

class SystemController extends \yii\easyii\components\Controller
{
    public $rootActions = ['*'];

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpdate()
    {
        $result = WebConsole::migrate();

        Setting::set('easyii_version', \yii\easyii\AdminModule::VERSION);
        Yii::$app->cache->flush();

        return $this->render('update', ['result' => $result]);
    }

    public function actionFlushCache()
    {
        Yii::$app->cache->flush();
        $this->flash('success', Yii::t('easyii', 'Cache flushed'));
        return $this->back();
    }

    public function actionClearAssets()
    {
        foreach(glob(Yii::$app->assetManager->basePath . DIRECTORY_SEPARATOR . '*') as $asset){
            if(is_link($asset)){
                unlink($asset);
            } elseif(is_dir($asset)){
                $this->deleteDir($asset);
            } else {
                unlink($asset);
            }
        }
        $this->flash('success', Yii::t('easyii', 'Assets cleared'));
        return $this->back();
    }

    public function actionLiveEdit($id)
    {
        Yii::$app->session->set('easyii_live_edit', $id);
        $this->back();
    }

    private function deleteDir($directory)
    {
        $iterator = new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        return rmdir($directory);
    }

    public function actionClearModuleSettings() {
        $activedModules = \yii\easyii\models\Module::findAllActive();
        $language = Data::getLocale();
        foreach(glob(Yii::getAlias('@easyii'). DIRECTORY_SEPARATOR .'modules/*') as $module) {

            $moduleName = basename($module);
            $moduleClass = 'yii\easyii\modules\\' . $moduleName . '\\' . ucfirst($moduleName) . 'Module';
            $moduleConfig = $moduleClass::$installConfig;

            $installedModule = $activedModules[$moduleName];
            $module = Module::findOne($installedModule->module_id);

            $module->title =  !empty($moduleConfig['title'][$language]) ? $moduleConfig['title'][$language] : $moduleConfig['title']['en'];
            $module->settings = Yii::createObject($moduleClass, [$moduleName])->settings;
            $module->order_num = $moduleConfig['order_num'];
            $module->icon = $moduleConfig['icon'];
            $module->status =  Module::STATUS_ON;

            $module->save();
        }
        return $this->back();
    }
}