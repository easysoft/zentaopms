#!/usr/bin/env php
<?php

/**

title=测试 testsuiteZen::checkTestsuiteAccess();
timeout=0
cid=19151

- 步骤1：正常情况，public类型属性name @接口测试套件2
- 步骤2：边界值，suiteID为0属性result @fail
- 步骤3：异常输入，不存在的suiteID属性result @fail
- 步骤4：admin访问private类型属性name @功能测试套件2
- 步骤5：用户访问自己创建的private属性name @接口测试套件1
- 步骤6：用户访问他人创建的private属性result @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testsuitezenTest.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('testsuite');
$table->id->range('1-10');
$table->product->range('1');
$table->project->range('1');
$table->name->range('功能测试套件1,接口测试套件1,性能测试套件1,安全测试套件1,功能测试套件2,接口测试套件2,性能测试套件2,安全测试套件2,功能测试套件3,接口测试套件3');
$table->desc->range('这是功能测试套件,这是接口测试套件,这是性能测试套件,这是安全测试套件,这是功能测试套件,这是接口测试套件,这是性能测试套件,这是安全测试套件,这是功能测试套件,这是接口测试套件');
$table->type->range('public,private,public,private,private,public,private,public,private,public');
$table->order->range('1-10');
$table->addedBy->range('admin,user1,admin,user1,admin,admin,user2,admin,user3,admin');
$table->addedDate->range('`2024-01-01 00:00:00`');
$table->lastEditedBy->range('admin');
$table->lastEditedDate->range('`2024-05-01 00:00:00`');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$zd_user = zenData('user');
$zd_user->id->range('1-4');
$zd_user->account->range('admin,user1,user2,user3');
$zd_user->realname->range('管理员,用户1,用户2,用户3');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(4);

su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testsuiteTest = new testsuiteTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testsuiteTest->checkTestsuiteAccessTest(6)) && p('name') && e('接口测试套件2'); // 步骤1：正常情况，public类型
r($testsuiteTest->checkTestsuiteAccessTest(0)) && p('result') && e('fail'); // 步骤2：边界值，suiteID为0
r($testsuiteTest->checkTestsuiteAccessTest(999)) && p('result') && e('fail'); // 步骤3：异常输入，不存在的suiteID
r($testsuiteTest->checkTestsuiteAccessTest(5)) && p('name') && e('功能测试套件2'); // 步骤4：admin访问private类型
su('user1');
r($testsuiteTest->checkTestsuiteAccessTest(2)) && p('name') && e('接口测试套件1'); // 步骤5：用户访问自己创建的private
r($testsuiteTest->checkTestsuiteAccessTest(5)) && p('result') && e('fail'); // 步骤6：用户访问他人创建的private
