#!/usr/bin/env php
<?php

/**

title=测试 screenModel::setFilterSQL();
cid=0

- 测试无过滤条件时的SQL @SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'
- 测试年份过滤条件 @SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 2023 = '2023'
- 测试账户过滤条件 @SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE admin = 'admin'
- 测试月份过滤条件 @SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 06 = '06'
- 测试部门过滤条件 @SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE admin IN ('admin','user1')

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

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
su('admin');

// 创建测试实例
$screenTest = new screenTest();

r($screenTest->setFilterSQLTest($chart, '', false)) && p('') && e("SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'");
r($screenTest->setFilterSQLTest($chart, 'year', true)) && p('') && e("SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 2023 = '2023'");
r($screenTest->setFilterSQLTest($chart, 'account', true)) && p('') && e("SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE admin = 'admin'");
r($screenTest->setFilterSQLTest($chart, 'month', true)) && p('') && e("SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE 06 = '06'");
r($screenTest->setFilterSQLTest($chart, 'dept', true)) && p('') && e("SELECT * FROM (SELECT id,name FROM zt_project WHERE type='program' AND parent=0 AND deleted='0') AS t1 WHERE admin IN ('admin','user1')");