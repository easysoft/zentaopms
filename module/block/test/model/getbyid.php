#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

/**

title=测试 blockModel->getByID();
timeout=0
cid=1

- 执行$data,属性module @qa
- 执行$data,属性title @测试统计
- 执行$data,属性source @qa
- 执行$data,属性block @statistic


*/

global $tester;
$tester->loadModel('block');

$data = $tester->block->getByID(15);

r($data) && p('module') && e('qa');        // 测试获取block的所属模块
r($data) && p('title')  && e('测试统计');  // 测试获取block的名称
r($data) && p('source') && e('qa');        // 测试获取block的来源
r($data) && p('block')  && e('statistic'); // 测试获取block的区块