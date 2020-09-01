<?php


namespace app\components\sudoku;

use app\components\sudoku\clients\ApplicationClient;
use app\components\sudoku\clients\RatchetApplicationClient;
use app\models\SudokuApiSource;
use app\models\SudokuRedisStorage;
use app\models\SudokuSourceInterface;
use app\models\SudokuStorageInterface;
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
        $app->set(SudokuSourceInterface::class, SudokuApiSource::class);
        $app->set(SudokuStorageInterface::class, SudokuRedisStorage::class);

        /**
         * Set components
         */
        $app->set('sudokuServer', EventLoop::class);
        $app->set('sudokuClient', RatchetApplicationClient::class);

        /**
         * @var ApplicationClient $client
         */
        $client = $app->get('sudokuClient');

        /**
         * @var ApplicationServer $server
         */
        $server = $app->get('sudokuServer');

        /**
         * links
         */
        $client->link($server);
        $server->link($client);
    }
}