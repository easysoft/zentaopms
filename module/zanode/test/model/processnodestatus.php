#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::processNodeStatus();
timeout=0
cid=19842

- 测试正常运行状态的节点，心跳超时
 - 属性id @1
 - 属性status @offline
- 测试运行状态的节点，心跳超时
 - 属性id @2
 - 属性status @offline
- 测试运行状态的节点，心跳超时
 - 属性id @3
 - 属性status @offline
- 测试运行状态的节点，心跳超时
 - 属性id @4
 - 属性status @offline
- 测试运行状态的节点，心跳超时
 - 属性id @5
 - 属性status @offline
- 测试ready状态的节点，心跳超时
 - 属性id @6
 - 属性status @offline
- 测试不存在的节点ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
zenData('host')->loadYaml('host')->gen(12);
zenData('image')->gen(5);

su('admin');

$zanodeTest = new zanodeModelTest();

r($zanodeTest->processNodeStatusTest(1)) && p('id,status') && e('1,offline');    // 测试正常运行状态的节点，心跳超时
r($zanodeTest->processNodeStatusTest(2)) && p('id,status') && e('2,offline');    // 测试运行状态的节点，心跳超时
r($zanodeTest->processNodeStatusTest(3)) && p('id,status') && e('3,offline');    // 测试运行状态的节点，心跳超时
r($zanodeTest->processNodeStatusTest(4)) && p('id,status') && e('4,offline');    // 测试运行状态的节点，心跳超时
r($zanodeTest->processNodeStatusTest(5)) && p('id,status') && e('5,offline');    // 测试运行状态的节点，心跳超时
r($zanodeTest->processNodeStatusTest(6)) && p('id,status') && e('6,offline');    // 测试ready状态的节点，心跳超时
r($zanodeTest->processNodeStatusTest(999)) && p() && e('0');                     // 测试不存在的节点ID