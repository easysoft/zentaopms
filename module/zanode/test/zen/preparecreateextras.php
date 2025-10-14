#!/usr/bin/env php
<?php

/**

title=测试 zanodeZen::prepareCreateExtras();
timeout=0
cid=0

- 执行zanodeTest模块的prepareCreateExtrasTest方法
 - 属性type @node
 - 属性parent @0
 - 属性status @offline
- 执行zanodeTest模块的prepareCreateExtrasTest方法
 - 属性type @node
 - 属性status @running
- 执行zanodeTest模块的prepareCreateExtrasTest方法
 - 属性parent @0
 - 属性osName @centOS79
- 执行$result属性result @fail
- 执行zanodeTest模块的prepareCreateExtrasTest方法
 - 属性type @node
 - 属性createdBy @admin

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanodezen.unittest.class.php';

// 准备测试用户
su('admin');

// 创建测试实例
$zanodeTest = new zanodeTest();

// 测试步骤1：测试物理主机创建请求数据处理
global $app;
$app->post = new stdClass();
$app->post->hostType = 'physics';
$app->post->name = 'test-physics-node';
$app->post->extranet = '192.168.1.100';
$app->post->osNamePhysics = 'ubuntu2004';
$app->user = new stdClass();
$app->user->account = 'admin';

r($zanodeTest->prepareCreateExtrasTest()) && p('type,parent,status') && e('node,0,offline');

// 测试步骤2：测试虚拟主机创建请求数据处理
$app->post = new stdClass();
$app->post->hostType = 'vm';
$app->post->name = 'test-vm-node';
$app->post->memory = 4.0;
$app->post->diskSize = 40.0;
$app->post->image = 1;
$app->user = new stdClass();
$app->user->account = 'admin';

r($zanodeTest->prepareCreateExtrasTest()) && p('type,status') && e('node,running');

// 测试步骤3：测试hostType为physics时必需字段变更
$app->post = new stdClass();
$app->post->hostType = 'physics';
$app->post->name = 'test-physics-node2';
$app->post->extranet = '192.168.1.101';
$app->post->osNamePhysics = 'centOS79';

r($zanodeTest->prepareCreateExtrasTest()) && p('parent,osName') && e('0,centOS79');

// 测试步骤4：测试虚拟主机但无Agent服务连接
$app->post = new stdClass();
$app->post->hostType = 'vm';
$app->post->name = 'test-vm-fail';
$app->post->memory = 2.0;
$app->post->diskSize = 20.0;
$app->post->image = 999;

// 由于测试环境无法连接真实的Agent服务，这里预期会失败
$result = $zanodeTest->prepareCreateExtrasTest();
r($result) && p('result') && e('fail');

// 测试步骤5：测试form::data()构建的数据对象默认值设置
$app->post = new stdClass();
$app->post->hostType = 'physics';
$app->post->name = 'test-defaults';
$app->post->extranet = '192.168.1.102';
$app->post->osNamePhysics = 'ubuntu2204';

r($zanodeTest->prepareCreateExtrasTest()) && p('type,createdBy') && e('node,admin');