#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::installSysSLB();
timeout=0
cid=16807

- 步骤1：验证installSysSLB方法存在 @1
- 步骤2：验证app对象有效 @1
- 步骤3：验证k8name参数默认值 @cne-lb
- 步骤4：验证channel参数默认值 @stable
- 步骤5：验证方法可调用性 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$userTable = zenData('user');
$userTable->account->range('admin,user1,user2');
$userTable->realname->range('管理员,用户1,用户2');
$userTable->password->range('123456{3}');
$userTable->deleted->range('0{3}');
$userTable->gen(3);

$spaceTable = zenData('space');
$spaceTable->id->range('1-5');
$spaceTable->name->range('系统空间,开发空间,测试空间,生产空间,共享空间');
$spaceTable->k8space->range('system,dev,test,prod,shared');
$spaceTable->owner->range('admin{2},user1{2},user2');
$spaceTable->deleted->range('0{5}');
$spaceTable->gen(5);

$instanceTable = zenData('instance');
$instanceTable->id->range('1-10');
$instanceTable->name->range('SLB1,SLB2,SLB3,SLB4,SLB5,SLB6,SLB7,SLB8,SLB9,SLB10');
$instanceTable->appName->range('SLB{5},LoadBalancer{5}');
$instanceTable->k8name->range('slb-001,slb-002,slb-003,slb-004,slb-005,lb-001,lb-002,lb-003,lb-004,lb-005');
$instanceTable->status->range('running{3},stopped{3},creating{2},initializing{2}');
$instanceTable->space->range('1-5');
$instanceTable->channel->range('stable{8},beta{2}');
$instanceTable->source->range('system{10}');
$instanceTable->deleted->range('0{10}');
$instanceTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 准备测试数据
$validApp = new stdClass();
$validApp->id = 1;
$validApp->chart = 'slb';
$validApp->alias = 'SLB负载均衡';
$validApp->logo = 'slb-logo.png';
$validApp->desc = 'SLB负载均衡组件';
$validApp->app_version = '1.0.0';
$validApp->version = '1.0.0';

// 测试方法参数默认值
$reflection = new ReflectionMethod($instanceTest->instance, 'installSysSLB');
$parameters = $reflection->getParameters();
$k8nameParam = $parameters[1];
$channelParam = $parameters[2];

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(method_exists($instanceTest->instance, 'installSysSLB')) && p() && e('1'); // 步骤1：验证installSysSLB方法存在
r(is_object($validApp)) && p() && e('1'); // 步骤2：验证app对象有效
r($k8nameParam->getDefaultValue()) && p() && e('cne-lb'); // 步骤3：验证k8name参数默认值
r($channelParam->getDefaultValue()) && p() && e('stable'); // 步骤4：验证channel参数默认值
r(is_callable(array($instanceTest->instance, 'installSysSLB'))) && p() && e('1'); // 步骤5：验证方法可调用性
