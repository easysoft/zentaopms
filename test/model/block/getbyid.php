#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getByID();
cid=1
pid=1

测试获取block的所属模块 >> my
测试获取block的名称 >> 项目统计
测试获取block的来源 >> project
测试获取block的区块 >> statistic

*/

$block = new blockTest();
$data = $block->getByIDTest(99);

r($data) && p('module') && e('my');        // 测试获取block的所属模块
r($data) && p('title')  && e('项目统计');  // 测试获取block的名称
r($data) && p('source') && e('project');   // 测试获取block的来源
r($data) && p('block')  && e('statistic'); // 测试获取block的区块