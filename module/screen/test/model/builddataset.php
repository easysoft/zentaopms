#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildDataset();
timeout=0
cid=18209

- 执行screenTest模块的buildDatasetTest方法，参数是20002, 'mysql', '' 第0条的count属性 @0
- 执行screenTest模块的buildDatasetTest方法，参数是20004, 'mysql', '' 第0条的count属性 @0
- 执行screenTest模块的buildDatasetTest方法，参数是20007, 'mysql', '' 第0条的count属性 @0
- 执行screenTest模块的buildDatasetTest方法，参数是10018, 'mysql', 'SELECT COUNT 第0条的count属性 @5
- 执行screenTest模块的buildDatasetTest方法，参数是999, 'mysql', 'SELECT 1 as test' 第0条的test属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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
$zd_user->id->range('1-5');
$zd_user->account->range('admin,user1,user2,user3,user4');
$zd_user->realname->range('管理员,用户1,用户2,用户3,用户4');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(5);

su('admin');

$screenTest = new screenModelTest();

r($screenTest->buildDatasetTest(20002, 'mysql', '')) && p('0:count') && e('0');
r($screenTest->buildDatasetTest(20004, 'mysql', '')) && p('0:count') && e('0');
r($screenTest->buildDatasetTest(20007, 'mysql', '')) && p('0:count') && e('0');
r($screenTest->buildDatasetTest(10018, 'mysql', 'SELECT COUNT(*) as count FROM zt_user')) && p('0:count') && e('5');
r($screenTest->buildDatasetTest(999, 'mysql', 'SELECT 1 as test')) && p('0:test') && e('1');
