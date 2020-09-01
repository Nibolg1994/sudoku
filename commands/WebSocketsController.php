<?php

namespace app\commands;


use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Yii;
use yii\console\Controller;

class WebSocketsController extends Controller
{
    /**
     * This command start WebSocket server for sudoku game
     */
    public function actionSudokuGame()
    {

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                   Yii::$app->get('client')
                )
            ),
            8080
        );

        $server->run();
    }
}
