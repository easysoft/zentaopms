#!/usr/bin/env php
<?php

/**

title=测试 zanodeZen::handleNode();
timeout=0
cid=0

- 测试步骤1：测试忙碌状态的节点操作（restoring状态） >> 期望返回忙碌错误信息
- 测试步骤2：测试忙碌状态的节点操作（creating_img状态） >> 期望返回忙碌错误信息
- 测试步骤3：测试忙碌状态的节点操作（creating_snap状态） >> 期望返回忙碌错误信息
- 测试步骤4：测试不存在的节点ID操作 >> 期望正常处理无节点情况
- 测试步骤5：测试正常状态节点操作但HTTP请求失败 >> 期望返回连接错误

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanodezen.unittest.class.php';

// 创建测试节点（物理节点，不需要parent）
$table = zenData('host');
$table->id->range('1-10');
$table->name->range('node1,node2,node3,node4,node5');
$table->type->range('node{5}');
$table->status->range('restoring,creating_img,creating_snap,running,running');
$table->extranet->range('192.168.1.10,192.168.1.11,192.168.1.12,192.168.1.13,192.168.1.14');
$table->zap->range('8080{5}');
$table->tokenSN->range('token1,token2,token3,token4,token5');
$table->parent->range('0{5}');
$table->hostType->range('physics{5}');
$table->gen(5);

su('admin');

$zanodeTest = new zanodeTest();

r($zanodeTest->handleNodeTest(1, 'boot')) && p('status,message') && e('fail,正在备份中，无法进行此操作');
r($zanodeTest->handleNodeTest(2, 'boot')) && p('status,message') && e('fail,正在创建镜像中，无法进行此操作');
r($zanodeTest->handleNodeTest(3, 'boot')) && p('status,message') && e('fail,正在创建快照中，无法进行此操作');
r($zanodeTest->handleNodeTest(999, 'boot')) && p('status,message') && e('fail,找不到ZA代理服务');
r($zanodeTest->handleNodeTest(4, 'boot')) && p('status,message') && e('fail,找不到ZA代理服务');