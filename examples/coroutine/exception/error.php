<?php

$redis = new Swoole\Coroutine\Redis();
go(function () use ($redis) {
    echo "create coro id ".co::getuid()."\n";
    $res = $redis->connect('127.0.0.1', 6379);
    var_dump($res);
    go(function () use ($redis) {
        echo "create coro id ".co::getuid()."\n";
        $data = $redis->get("key");
        var_dump($data);
    });//step 2协程退出后 pop 读排队队列 resume 到 coro 3
    go(function () use ($redis) {
        echo "create coro id ".co::getuid()."\n";
        //step 1检查如果绑定读 yield 加入读排队队列
        //step 3 resume from coro 2
        $data = $redis->get("key1");
        var_dump($data);
    });
});
