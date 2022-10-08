#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getClosedBlockPairs();
cid=1
pid=1

测试获取关闭的区块键值对 >> 未获取到关闭的区域

*/

global $config;
$block = new blockTest();
$data = $block->getClosedBlockPairsTest('');

r($data) && p('massage') && e('未获取到关闭的区域'); //测试获取关闭的区块键值对