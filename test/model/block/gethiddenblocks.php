#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getHiddenBlocks();
cid=1
pid=1

测试获取隐藏的区块 >> 未获取到隐藏的区块

*/

$block = new blockTest();

r($block->getHiddenBlocksTest('my')) && p('message') && e('未获取到隐藏的区块'); //测试获取隐藏的区块