#!/usr/bin/env php
<?php

/**

title=测试 zanodeZen::getTaskStatus();
timeout=0
cid=0

- 测试步骤1: 指定taskID和type查询export类型任务
 - 属性task @1001
 - 属性type @export
 - 属性status @completed
- 测试步骤2: 指定taskID和type查询import类型任务
 - 属性task @1002
 - 属性type @import
 - 属性status @running
- 测试步骤3: 指定status查询running状态任务列表 @array
- 测试步骤4: 不指定任何条件返回所有任务数据 @string
- 测试步骤5: HTTP请求失败返回false @0
- 测试步骤6: 空数据返回空数组 @array
- 测试步骤7: 指定status查询completed状态任务列表 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanodezen.unittest.class.php';

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
global $app;
if(!isset($app->rawModule)) $app->rawModule = 'zanode';
if(!isset($app->rawMethod)) $app->rawMethod = 'test';

su('admin');

$zanodeTest = new zanodeTest();

// 测试场景1: 查询指定taskID和type的任务(export类型,已完成)
$node1 = new stdClass();
$node1->ip = '192.168.1.100';
$node1->hzap = '8086';
$node1->tokenSN = 'test-token-1';

// 测试场景2: 查询指定taskID和type的任务(import类型,运行中)
$node2 = new stdClass();
$node2->ip = '192.168.1.100';
$node2->hzap = '8086';
$node2->tokenSN = 'test-token-2';

// 测试场景3: 查询指定status的任务列表
$node3 = new stdClass();
$node3->ip = '192.168.1.100';
$node3->hzap = '8086';
$node3->tokenSN = 'test-token-3';

// 测试场景4: 不指定条件返回所有数据
$node4 = new stdClass();
$node4->ip = '192.168.1.100';
$node4->hzap = '8086';
$node4->tokenSN = 'test-token-4';

// 测试场景5: HTTP请求失败
$node5 = new stdClass();
$node5->ip = '192.168.1.404';
$node5->hzap = '8086';
$node5->tokenSN = 'test-token-5';

// 测试场景6: 空数据返回空数组
$node6 = new stdClass();
$node6->ip = '192.168.1.200';
$node6->hzap = '8086';
$node6->tokenSN = 'test-token-6';

// 测试场景7: 查询completed状态任务列表
$node7 = new stdClass();
$node7->ip = '192.168.1.100';
$node7->hzap = '8086';
$node7->tokenSN = 'test-token-7';

r($zanodeTest->getTaskStatusTest($node1, 1001, 'export', '')) && p('task,type,status') && e('1001,export,completed'); // 测试步骤1: 指定taskID和type查询export类型任务
r($zanodeTest->getTaskStatusTest($node2, 1002, 'import', '')) && p('task,type,status') && e('1002,import,running'); // 测试步骤2: 指定taskID和type查询import类型任务
r(gettype($zanodeTest->getTaskStatusTest($node3, 0, '', 'running'))) && p() && e('array'); // 测试步骤3: 指定status查询running状态任务列表
r(gettype($zanodeTest->getTaskStatusTest($node4, 0, '', ''))) && p() && e('string'); // 测试步骤4: 不指定任何条件返回所有任务数据
r($zanodeTest->getTaskStatusTest($node5, 0, '', '')) && p() && e('0'); // 测试步骤5: HTTP请求失败返回false
r(gettype($zanodeTest->getTaskStatusTest($node6, 0, '', ''))) && p() && e('array'); // 测试步骤6: 空数据返回空数组
r(gettype($zanodeTest->getTaskStatusTest($node7, 0, '', 'completed'))) && p() && e('array'); // 测试步骤7: 指定status查询completed状态任务列表