#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getLastKey();
cid=1
pid=1

测试通过qa模块获取区块的key >> 4
测试通过my模块获取区块的key >> 10

*/

$block = new blockTest();
$data[0] = $block->getLastKeyTest('qa');
$data[1] = $block->getLastKeyTest('my');

r($data[0]) && p('qa') && e('4');  //测试通过qa模块获取区块的key
r($data[1]) && p('my') && e('10'); //测试通过my模块获取区块的key