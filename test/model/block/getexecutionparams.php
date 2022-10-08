#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getExecutionParams();
cid=1
pid=1

测试获取执行区块的参数 >> 数量;类型

*/

$block = new blockTest();
$data = $block->getExecutionParamsTest();

r($data) && p('count:name;type:name') && e('数量;类型'); //测试获取执行区块的参数