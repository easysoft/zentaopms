#!/usr/bin/env php
<?php

/**

title=测试 screenModel::setFilterSQL();
timeout=0
cid=18282

- 执行screenTest模块的setFilterSQLTest方法，参数是$chart, '', false  @SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'

- 执行screenTest模块的setFilterSQLTest方法，参数是$chart, 'year', true  @SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'

- 执行screenTest模块的setFilterSQLTest方法，参数是$chart, 'account', true  @SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE admin = 'admin'

- 执行screenTest模块的setFilterSQLTest方法，参数是$chart, 'month', true  @SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'

- 执行screenTest模块的setFilterSQLTest方法，参数是$chart, 'dept', true  @SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE admin IN ('admin','user1')

- 执行screenTest模块的setFilterSQLTest方法，参数是$chart, 'multiple', true  @SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0' AND 2023 = '2023'

- 执行screenTest模块的setFilterSQLTest方法，参数是new stdclass  @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$user = zenData('user');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->dept->range('1,1,2');
$user->gen(3);

$dept = zenData('dept');
$dept->id->range('1,2');
$dept->name->range('开发部,测试部');
$dept->path->range(',1,,1,2,');
$dept->gen(2);

// 创建模拟chart对象
$chart = new stdclass();
$chart->id = 1018;
$chart->sql = "SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'";

// 登录用户
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
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

// 创建测试实例
$screenTest = new screenModelTest();

r($screenTest->setFilterSQLTest($chart, '', false)) && p('') && e("SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'");
r($screenTest->setFilterSQLTest($chart, 'year', true)) && p('') && e("SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'");
r($screenTest->setFilterSQLTest($chart, 'account', true)) && p('') && e("SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE admin = 'admin'");
r($screenTest->setFilterSQLTest($chart, 'month', true)) && p('') && e("SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'");
r($screenTest->setFilterSQLTest($chart, 'dept', true)) && p('') && e("SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'");
r($screenTest->setFilterSQLTest($chart, 'multiple', true)) && p('') && e("SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'");
r($screenTest->setFilterSQLTest($chart, '', true)) && p('') && e("SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'");
