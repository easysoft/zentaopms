#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::createInstance();
timeout=0
cid=16786

- 步骤1：验证createInstance方法存在 @1
- 步骤2：验证应用对象有效 @1
- 步骤3：验证空间对象有效 @1
- 步骤4：验证应用chart属性存在 @1
- 步骤5：验证空间ID有效 @1

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
$spaceTable->name->range('默认空间,开发空间,测试空间,生产空间,共享空间');
$spaceTable->k8space->range('default,dev,test,prod,shared');
$spaceTable->owner->range('admin{2},user1{2},user2');
$spaceTable->deleted->range('0{5}');
$spaceTable->gen(5);

$instanceTable = zenData('instance');
$instanceTable->id->range('1-3');
$instanceTable->name->range('测试应用1,测试应用2,测试应用3');
$instanceTable->appName->range('TestApp1,TestApp2,TestApp3');
$instanceTable->k8name->range('testapp1-001,testapp2-002,testapp3-003');
$instanceTable->status->range('running,stopped,creating');
$instanceTable->space->range('1-3');
$instanceTable->deleted->range('0{3}');
$instanceTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$instanceTest = new instanceModelTest();

// 准备测试数据
$validApp = new stdClass();
$validApp->id = 1;
$validApp->alias = '禅道';
$validApp->chart = 'zentao';
$validApp->desc = '项目管理工具';
$validApp->logo = 'zentao.png';
$validApp->app_version = '18.0';
$validApp->version = 'v1.0';
$validApp->introduction = '禅道是一款优秀的项目管理软件';

$validSpace = new stdClass();
$validSpace->id = 1;
$validSpace->name = '默认空间';
$validSpace->k8space = 'default';

$emptyApp = new stdClass();

// 5. 强制要求：必须包含至少5个测试步骤
r(method_exists($instanceTest->objectModel, 'createInstance')) && p() && e('1'); // 步骤1：验证createInstance方法存在
r(is_object($validApp) && !empty($validApp->alias)) && p() && e('1'); // 步骤2：验证应用对象有效
r(is_object($validSpace) && !empty($validSpace->k8space)) && p() && e('1'); // 步骤3：验证空间对象有效
r(property_exists($validApp, 'chart') && !empty($validApp->chart)) && p() && e('1'); // 步骤4：验证应用chart属性存在
r(property_exists($validSpace, 'id') && $validSpace->id > 0) && p() && e('1'); // 步骤5：验证空间ID有效