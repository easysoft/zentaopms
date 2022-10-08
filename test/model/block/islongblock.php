#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->isLongBlock();
cid=1
pid=1

测试 空 对象 检查是否为长区块 >> 2
测试 对象grid为 5 的时候 检查是否为长区块 >> 2
测试 对象grid为 6 的时候 检查是否为长区块 >> 1
测试 对象grid为 7 的时候 检查是否为长区块 >> 1

*/

$block1 = new stdclass();

$block2 = new stdclass();
$block2->grid = 5;

$block3 = new stdclass();
$block3->grid = 6;

$block4 = new stdclass();
$block4->grid = 7;

$block = new blockTest();

r($block->isLongBlockTest($block1)) && p() && e('2'); // 测试 空 对象 检查是否为长区块
r($block->isLongBlockTest($block2)) && p() && e('2'); // 测试 对象grid为 5 的时候 检查是否为长区块
r($block->isLongBlockTest($block3)) && p() && e('1'); // 测试 对象grid为 6 的时候 检查是否为长区块
r($block->isLongBlockTest($block4)) && p() && e('1'); // 测试 对象grid为 7 的时候 检查是否为长区块