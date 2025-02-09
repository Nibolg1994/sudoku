<?php

namespace app\commands;

use app\components\sudoku\Bootstrap;
use app\components\sudoku\clients\RatchetApplicationClient;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

/**
 * Class WebSocketsController
 * @package app\commands
 */
class WebSocketsController extends Controller
{

    /**
     * This command start WebSocket server for sudoku game
     * @throws
     */
    public function actionSudokuGame()
    {
        /**
         * Load components
         */
        $bootstrap = new Bootstrap();
        $bootstrap->bootstrap(Yii::$app);

        /**
         * @var $ratchetComponent RatchetApplicationClient
         */
        $ratchetComponent = Yii::$app->get('sudokuClient');

        $wsServer = new WsServer($ratchetComponent);

        $server = IoServer::factory(
            new HttpServer($wsServer),
            ArrayHelper::getValue(Yii::$app->params, ['websocket', 'port'])
        );

        $wsServer->enableKeepAlive(
            $server->loop,
            ArrayHelper::getValue(Yii::$app->params, ['websocket', 'keep-alive-interval'])
        );

        $this->stdout('server run' . PHP_EOL, Console::FG_GREEN);
        $server->run();
    }
}
