#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';
su('admin');

/**

title=测试 blockModel->getExecutionStatisticParams();
cid=1
pid=1

测试获取执行区块的参数 >> 数量;类型

*/

$block = new blockTest();
$data = $block->getExecutionStatisticParamsTest();

r($data) && p('count:name;type:name') && e('数量;类型'); //测试获取执行区块的参数