<?php
/**
 * Created by PhpStorm.
 * User: aushev
 * Date: 07.11.2019
 * Time: 7:44
 */

namespace App\Models\Views\Order;


class OrderViewDataModel
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
    public $time;

    /**
     * @var string
     */
    public $letter;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var bool
     */
    public $active = false;

    /**
     * @var integer
     */
    public $status;

    public function __construct($order)
    {
        $this->name   = $order['name'];
        $this->letter = mb_substr($order['name'], 0, 1);
        $this->id     = $order['id'];
        $this->time   = date("H:i", strtotime($order['date_delivery']));
        $this->phone  = $order['phone'];
        $this->status = $order['status'];
    }
}
