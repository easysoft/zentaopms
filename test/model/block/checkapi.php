#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->checkAPI();
cid=1
pid=1

测试空哈希值 >> 0
测试正确的哈希值 >> 1
测试错误的哈希值 >> 0

*/

$block = new blockTest();

r($block->checkAPITest('')) && p('') && e('0'); // 测试空哈希值
r($block->checkAPITest('858640a724c2c981983935eb2bbc4ad8')) && p('') && e('1'); // 测试正确的哈希值
r($block->checkAPITest('858640a724c2c98198')) && p('') && e('0'); // 测试错误的哈希值
