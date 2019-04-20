<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 2019/3/28
 * Time: 10:29 AM
 */

namespace app\index\controller;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Rabbit
{
    const exchange = 'router';
    const queue    = 'msgs';
    public static function pushMessage($data)
    {
        $connection = new AMQPStreamConnection('127.0.0.1', '5672', 'gust', 'gust', '/');
        $channel    = $connection->channel();

        $channel->queue_declare(self::queue, false, true, false, false);
        $channel->exchange_declare(self::exchange, 'direct', false, true, false);
        $channel->queue_bind(self::queue, self::exchange);
        $messageBody = $data;
        $message     = new AMQPMessage($messageBody, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($message, self::exchange);
        $channel->close();
        $connection->close();
        return 'ok';
    }
}
