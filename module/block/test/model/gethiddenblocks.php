#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';
su('admin');

/**

title=测试 blockModel->getHiddenBlocks();
cid=1
pid=1

测试获取隐藏的区块 >> 未获取到隐藏的区块

*/

$block = new blockTest();

r($block->getHiddenBlocksTest('my')) && p('message') && e('未获取到隐藏的区块'); //测试获取隐藏的区块