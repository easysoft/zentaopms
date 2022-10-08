#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getAvailableBlocks();
cid=1
pid=1

通过获取项目的可用区块 >> 项目计划,最新动态
通过获取测试的可用区块 >> 测试统计,Bug列表,用例列表,版本列表

*/

$block = new blockTest();
$data[0] = $block->getAvailableBlocksTest('project', 'project', 'waterfall');
$data[1] = $block->getAvailableBlocksTest('qa');

r($data[0]) && p('waterfallgantt,projectdynamic') && e('项目计划,最新动态');                // 通过获取项目的可用区块
r($data[1]) && p('statistic,bug,case,testtask') && e('测试统计,Bug列表,用例列表,版本列表'); // 通过获取测试的可用区块