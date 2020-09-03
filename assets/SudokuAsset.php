<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 29.08.2020
 * Time: 18:29
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class SudokuAsset
 * @package app\assets
 */
class SudokuAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/sudoku.css',
    ];
    public $js = [
        'js/sudoku.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}