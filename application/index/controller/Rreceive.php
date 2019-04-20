<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 2019/3/28
 * Time: 6:25 PM
 */

namespace app\index\controller;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class Rreceive
{
    public function creceive()
    {
        $connection = new AMQPStreamConnection('127.0.0.1', '5672', 'guest', 'guest', '/');
        $channel    = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);
        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };
        $channel->basic_consume('hello', '', false, true, false, false, $callback);
        while (count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }
}
