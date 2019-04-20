<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 2019/3/28
 * Time: 6:14 PM
 */

namespace app\index\controller;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Rsend {
	public function publishsend()
	{
		$connection = new AMQPStreamConnection('127.0.0.1','5672','guest','guest');
		$channel = $connection->channel();
		//发送方其实不需要设置队列， 不过对于持久化有关，建议执行该行
		$channel->queue_declare('hello', false, false, false, false);
//		$msg = new AMQPMessage('Hello World!');
		$msg = new AMQPMessage('Hello World!');
		$channel->basic_publish($msg, '', 'hello');
		echo " [x] Sent 'Hello World!'\n";
		$channel->close();
		$connection->close();
	}
}