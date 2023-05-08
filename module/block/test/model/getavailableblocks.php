#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';

/**

title=测试 blockModel->getAvailableBlocks();
cid=1
pid=1

*/

$block = new blockTest();
$data[0] = $block->getAvailableBlocksTest('waterfallProject');
$data[1] = $block->getAvailableBlocksTest('qa');

r($data[0]) && p('waterfallgantt,projectdynamic') && e('项目计划,最新动态');                // 通过获取项目的可用区块
r($data[1]) && p('statistic,bug,case,testtask') && e('测试统计,Bug列表,用例列表,版本列表'); // 通过获取测试的可用区块
