<?php


namespace app\components\sudoku;

use app\components\sudoku\clients\ApplicationClient;
use app\components\sudoku\clients\RatchetApplicationClient;
use app\models\SudokuApiSource;
use app\models\SudokuRedisStorage;
use app\models\SudokuSourceInterface;
use app\models\SudokuStorageInterface;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;


/**
 * Class Bootstrap
 * @package app\components\sudoku
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
        /**
         * Set interfaces
         */
        Yii::$container->set(SudokuSourceInterface::class, SudokuApiSource::class);
        Yii::$container->set(SudokuStorageInterface::class, SudokuRedisStorage::class);
        Yii::$container->setSingleton(ApplicationServer::class, EventLoop::class);
        Yii::$container->setSingleton(ApplicationClient::class, RatchetApplicationClient::class);

        /**
         * @var $clientComponent ApplicationClient
         */
        $clientComponent = Yii::$container->get(ApplicationClient::class);
        /**
         * @var $serverComponent ApplicationServer
         */
        $serverComponent = Yii::$container->get(ApplicationServer::class);

        $clientComponent->link($serverComponent);
        $serverComponent->link($clientComponent);

        $app->set('sudokuClient', $clientComponent);
        $app->set('sudokuServer', $serverComponent);
    }
}