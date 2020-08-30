<?php

namespace app\commands;

use app\components\websockets\SugokuApp;
use app\models\SudokuApiSource;
use app\models\SudokuGame;
use app\models\SudokuInitializer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use yii\console\Controller;

class WebSocketsController extends Controller
{
    /**
     * This command start WebSocket server for sudoku game
     */
    public function actionSudokuGame()
    {
        $game = new SudokuGame(
            new SudokuApiSource()
        );
        print_r($game->getCountFreeCells());
        exit;

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new SugokuApp()
                )
            ),
            8080
        );

        $server->run();
    }
}
