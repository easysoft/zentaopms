#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/block.class.php';
su('admin');

/**

title=测试 blockModel->getClosedBlockPairs();
timeout=0
cid=1

- 测试获取关闭的区块键值对属性product|list @产品|产品列表

- 测试获取关闭的区块键值对属性assigntome|assigntome @待处理

*/

global $tester;
$tester->loadModel('block');
$data = $tester->block->getClosedBlockPairs('product|list,assigntome|assigntome');
r($data) && p('product|list') && e('产品|产品列表');   //测试获取关闭的区块键值对
r($data) && p('assigntome|assigntome') && e('待处理'); //测试获取关闭的区块键值对
