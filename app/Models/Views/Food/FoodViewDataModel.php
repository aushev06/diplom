<?php
/**
 * Created by PhpStorm.
 * User: aushev
 * Date: 07.11.2019
 * Time: 7:44
 */

namespace App\Models\Views\Food;


class FoodViewDataModel
{
    /**
     * @var integer $id
     */
    public $id;
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $letter;

    /**
     * @var  string
     */
    public $img;

    /**
     * @var integer
     */
    public $count;


    public function __construct($food)
    {
        $this->name   = $food['name'];
        $this->letter = mb_substr($food['name'], 0, 1);
        $this->id     = $food['id'];
        $this->img    = isset($food['img']) ? url('storage/' . $food['img']) : null;
        $this->count  = isset($food['count']) ? $food['count'] : null;
    }
}
