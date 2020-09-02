<?php

namespace app\components\sudoku\clients;

use app\components\sudoku\ApplicationServer;
use yii\base\Component;

/**
 * Class ApplicationClient
 * @package app\components\sudoku\clients
 */
abstract class ApplicationClient extends Component implements ClientApplicationInterface
{
    /**
     * Link to application server class
     * @param ApplicationServer $server
     */
    public function link(ApplicationServer $server)
    {
        $this->on(
            ClientApplicationInterface::EVENT_MOVE_REQUEST,
            [$server, 'move']
        );

        $this->on(
            ClientApplicationInterface::EVENT_START_GAME_REQUEST,
            [$server, 'start']
        );

        $this->on(
            ClientApplicationInterface::EVENT_SHOW_TOP_LIST_REQUEST,
            [$server, 'topList']
        );

        $this->on(
            ClientApplicationInterface::EVENT_DISCONNECT,
            [$server, 'disconnect']
        );
    }
}