<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 2019/3/27
 * Time: 3:50 PM
 */

namespace app\index\controller;

use think\Queue;

class JobTest
{
    public function actionWithHelloJob()
    {
        // 1.当前任务将由哪个类来负责处理。
        //   当轮到该任务时，系统将生成一个该类的实例，并调用其 fire 方法
        $jobHandlerClassName  = 'app\index\job\Hello';

        // 2.当前任务归属的队列名称，如果为新队列，会自动创建
        $jobQueueName  	  = 'helloJobQueue';

        // 3.当前任务所需的业务数据 . 不能为 resource 类型，其他类型最终将转化为json形式的字符串
        //   ( jobData 为对象时，存储其public属性的键值对 )
        $jobData       	  = [ 'ts' => time(), 'bizId' => uniqid() , 'a' => 1 ];

        // 4.将该任务推送到消息队列，等待对应的消费者去执行
        $isPushed = Queue::push($jobHandlerClassName, $jobData, $jobQueueName);

        // database 驱动时，返回值为 1|false  ;   redis 驱动时，返回值为 随机字符串|false
        if ($isPushed !== false) {
            echo date('Y-m-d H:i:s') . ' a new Hello Job is Pushed to the MQ'.'<br>';
        } else {
            echo 'Oops, something went wrong.';
        }
    }

	/**
	 * 队列多任务
	 */
	public function actionWithMultiTask(){

		$taskType = $_GET['taskType'];
		switch ($taskType) {
			case 'taskA':
				$jobHandlerClassName  = 'app\index\job\MultiTask@taskA';
				$jobDataArr = ['a'	=> '1'];
				$jobQueueName = "multiTaskJobQueue";
				break;
			case 'taskB':
				$jobHandlerClassName  = 'app\index\job\MultiTask@taskB';
				$jobDataArr = ['b'	=> '2'];
				$jobQueueName = "multiTaskJobQueue";
				break;
			default:
				break;
		}
//print_r($jobDataArr);die;
		$isPushed = Queue::push($jobHandlerClassName, $jobDataArr, $jobQueueName);
		if ($isPushed !== false) {
			echo("the $taskType of MultiTask Job has been Pushed to ".$jobQueueName ."<br>");
		}else{
			throw new Exception("push a new $taskType of MultiTask Job Failed!");
		}
	}
}
