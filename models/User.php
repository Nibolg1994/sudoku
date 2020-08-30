<?php


namespace app\models;

use yii\base\Model;

/**
 * Class User
 * @package app\models
 */
class User extends Model
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'id'], 'required'],
            [['name'], 'string', 'max' => 45],
        ];
    }

}