#!/usr/bin/env php
<?php

/**

title=测试 zanodeZen::getTaskStatus();
timeout=0
cid=0

- 步骤1：正常获取所有任务状态 @object
- 步骤2：获取运行中的任务 @array
- 步骤3：获取特定任务
 - 属性task @1001
 - 属性type @export
 - 属性status @completed
- 步骤4：API请求失败 @false
- 步骤5：API返回空数据 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanodezen.unittest.class.php';

// 创建测试数据
$table = zenData('host');
$table->id->range('1-5');
$table->name->range('node1,node2,node3,node4,node5');
$table->type->range('node{5}');
$table->status->range('running{5}');
$table->extranet->range('192.168.1.100,192.168.1.200,192.168.1.404,192.168.1.101,192.168.1.102');
$table->zap->range('8080{5}');
$table->tokenSN->range('token1,token2,token3,token4,token5');
$table->parent->range('0{5}');
$table->hostType->range('kvm{5}');
$table->gen(5);

su('admin');

$zanodeTest = new zanodeTest();

// 创建测试节点对象
$node1 = new stdClass();
$node1->id = 1;
$node1->ip = '192.168.1.100';
$node1->hzap = '8080';
$node1->tokenSN = 'token1';

$node2 = new stdClass();
$node2->id = 2;
$node2->ip = '192.168.1.200';
$node2->hzap = '8080';
$node2->tokenSN = 'token2';

$node3 = new stdClass();
$node3->id = 3;
$node3->ip = '192.168.1.404';
$node3->hzap = '8080';
$node3->tokenSN = 'token3';

// 执行测试步骤（至少5个）
r($zanodeTest->getTaskStatusTest($node1)) && p() && e('object'); // 步骤1：正常获取所有任务状态
r($zanodeTest->getTaskStatusTest($node1, 0, '', 'running')) && p() && e('array'); // 步骤2：获取运行中的任务
r($zanodeTest->getTaskStatusTest($node1, 1001, 'export')) && p('task,type,status') && e('1001,export,completed'); // 步骤3：获取特定任务
r($zanodeTest->getTaskStatusTest($node3)) && p() && e('false'); // 步骤4：API请求失败
r($zanodeTest->getTaskStatusTest($node2)) && p() && e('array'); // 步骤5：API返回空数据