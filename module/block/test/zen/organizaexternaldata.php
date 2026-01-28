#!/usr/bin/env php
<?php

/**

title=测试 blockZen::organizaExternalData();
timeout=0
cid=15246

- 步骤1：正常用户参数第user条的account属性 @admin
- 步骤2：空参数第user条的account属性 @guest
- 步骤3：无效用户账号第user条的account属性 @guest
- 步骤4：语言和SSO设置属性sso @test=1
- 步骤5：SSO参数包含问号属性sign @&

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('user');
$table->id->range('1-10');
$table->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$table->password->range('123456{10}');
$table->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$table->role->range('admin{1},dev{4},test{5}');
$table->ranzhi->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$table->dept->range('1-5');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 准备测试block对象
$validBlock = new stdclass();
$validBlock->params = new stdclass();
$validBlock->params->account = 'admin';

$emptyBlock = new stdclass();

$invalidBlock = new stdclass();
$invalidBlock->params = new stdclass();
$invalidBlock->params->account = 'nonexistent';

$_GET['lang'] = 'en';
r($blockTest->organizaExternalDataTest($validBlock)) && p('user:account') && e('admin'); // 步骤1：正常用户参数

$_GET = array();
r($blockTest->organizaExternalDataTest($emptyBlock)) && p('user:account') && e('guest'); // 步骤2：空参数

$_GET = array();
r($blockTest->organizaExternalDataTest($invalidBlock)) && p('user:account') && e('guest'); // 步骤3：无效用户账号

$_GET['lang'] = 'zh_cn';
$_GET['sso'] = base64_encode('test=1');
r($blockTest->organizaExternalDataTest($validBlock)) && p('sso') && e('test=1'); // 步骤4：语言和SSO设置

$_GET['sso'] = base64_encode('test=1?param=value');
r($blockTest->organizaExternalDataTest($validBlock)) && p('sign') && e('&'); // 步骤5：SSO参数包含问号