<?php
/**
 * Created by PhpStorm.
 * User: xz
 * Date: 2017/05/27
 * Time: 10:07
 */

use Workerman\Worker;
require_once './Autoloader.php';

$worker = new Worker("websocket://0.0.0.0:2346");

// 启动4个进程对外提供服务
$worker->count = 4;

// 当收到客户端发来的数据后返回hello $data给客户端

$connect = $worker->connections;

$worker->onMessage = function($connection, $data)
{
    global $worker;
    // 向客户端发送hello $data
    $user = [
        'id' => $connection->id,
        'data' => $data
    ];
    foreach ($worker->connections as $value){
        if($value->id != $user['id']){
            $value->send(json_encode($user));
        }
    }
};


// 运行
Worker::runAll();