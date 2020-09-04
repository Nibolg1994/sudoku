<?php

use \app\assets\SudokuAsset;

/* @var $this yii\web\View */
/* @var array $rows */

$this->title = 'My Yii Application';
SudokuAsset::register($this);
$index = 1;
?>
<div class="buttons">
    <button id="btn-start" disabled class="btn-action btn btn-primary">Начать игру</button>
    <button id="btn-list" disabled class="btn-action btn btn-primary">Просмотреть топ лист</button>
</div>
<div id="sudoku-board" style="display:none">
<table>
    <?php foreach ($rows as $i) :?>
        <tr>
            <?php foreach ($rows as $j) :?>
                <td><input type="text" class="cell" id="cell<?= $index ?>" data-id="<?= $index++ ?>" maxlength="1"></td>
            <?php endforeach;?>
        </tr>
    <?php endforeach;?>
</table>
</div>
<div id="topList" style="display:none">
    <h3>Топ лист игрков</h3>
    <ul id="list_users"></ul>
</div>