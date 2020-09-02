<?php

namespace app\components\sudoku;

use app\components\sudoku\clients\ApplicationClient;
use yii\base\Component;

/**
 * Class ApplicationServer
 * @package app\components\sudoku
 */
abstract class ApplicationServer extends Component implements ServerApplicationInterface
{
    /**
     * Link to application server class
     * @param ApplicationClient $client
     */
    public function link(ApplicationClient $client)
    {
        $this->on(
            ApplicationServer::EVENT_MOVE_RESPONSE,
            [$client, 'sendBroadcastEvent']
        );

        $this->on(
            ApplicationServer::EVENT_SHOW_TOP_LIST_RESPONSE,
            [$client, 'sendEvent']
        );

        $this->on(
            ApplicationServer::EVENT_START_GAME_ACCEPT,
            [$client, 'sendEvent']
        );

        $this->on(
            ApplicationServer::EVENT_ERROR,
            [$client, 'sendEvent']
        );
    }
}