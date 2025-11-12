#!/usr/bin/env php
<?php

/**

title=测试 zanodeZen::prepareCreateExtras();
timeout=0
cid=0

- 测试步骤1: 虚拟主机创建正常场景
 - 属性type @node
 - 属性status @running
 - 属性name @test-node-1
- 测试步骤2: 物理主机创建正常场景
 - 属性type @node
 - 属性status @offline
 - 属性parent @0
 - 属性osName @CentOS 7
- 测试步骤3: 虚拟主机创建缺少必填字段name
 - 属性result @fail
 - 属性message @name is required
- 测试步骤4: 物理主机创建验证字段设置
 - 属性parent @0
 - 属性status @offline
 - 属性name @test-node-3
- 测试步骤5: 虚拟主机linkAgentService调用失败
 - 属性result @fail
 - 属性message @Agent service connection failed

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanodezen.unittest.class.php';

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
global $app;
if(!isset($app->rawModule)) $app->rawModule = 'zanode';
if(!isset($app->rawMethod)) $app->rawMethod = 'test';

zenData('host')->gen(0);

su('admin');

$zanodeTest = new zanodeTest();

// 测试步骤1: 虚拟主机创建正常场景
$app->post = new stdClass();
$app->post->hostType = 'virtual';
$app->post->name = 'test-node-1';
$app->post->extranet = '192.168.1.100';
$app->post->image = 1;
$app->post->cpuCores = 4;
$app->post->memory = 8;
$app->post->diskSize = 100;
$app->post->osName = 'Ubuntu 20.04';
r($zanodeTest->prepareCreateExtrasTest()) && p('type,status,name') && e('node,running,test-node-1'); // 测试步骤1: 虚拟主机创建正常场景

// 测试步骤2: 物理主机创建正常场景
$app->post = new stdClass();
$app->post->hostType = 'physics';
$app->post->name = 'test-node-2';
$app->post->extranet = '192.168.1.101';
$app->post->osNamePhysics = 'CentOS 7';
r($zanodeTest->prepareCreateExtrasTest()) && p('type,status,parent,osName') && e('node,offline,0,CentOS 7'); // 测试步骤2: 物理主机创建正常场景

// 测试步骤3: 虚拟主机创建缺少必填字段name
$app->post = new stdClass();
$app->post->hostType = 'virtual';
$app->post->image = 1;
r($zanodeTest->prepareCreateExtrasTest()) && p('result,message') && e('fail,name is required'); // 测试步骤3: 虚拟主机创建缺少必填字段name

// 测试步骤4: 物理主机创建验证字段设置
$app->post = new stdClass();
$app->post->hostType = 'physics';
$app->post->name = 'test-node-3';
$app->post->extranet = '192.168.1.102';
$app->post->osNamePhysics = 'Ubuntu 22.04';
r($zanodeTest->prepareCreateExtrasTest()) && p('parent,status,name') && e('0,offline,test-node-3'); // 测试步骤4: 物理主机创建验证字段设置

// 测试步骤5: 虚拟主机linkAgentService调用失败
$app->post = new stdClass();
$app->post->hostType = 'virtual';
$app->post->name = 'test-node-4';
$app->post->extranet = '192.168.1.103';
$app->post->image = 999;
$app->post->cpuCores = 2;
$app->post->memory = 4;
$app->post->diskSize = 50;
$app->post->osName = 'Debian 11';
r($zanodeTest->prepareCreateExtrasTest()) && p('result,message') && e('fail,Agent service connection failed'); // 测试步骤5: 虚拟主机linkAgentService调用失败