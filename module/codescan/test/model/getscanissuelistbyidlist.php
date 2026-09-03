#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
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

/**

title=测试 codescanModel->getScanIssueListByIdList();
timeout=0
cid=0

- 查询单个问题 ID 返回空列表 @0
- 查询两个问题 ID 返回空列表 @0
- 查询空问题 ID 列表直接返回成功 @1
- 查询不存在的问题 ID 返回空列表 @0
- 查询零号问题 ID 返回空列表 @0

*/

su('admin');
$test = new codescanModelTest();

r($test->getScanIssueListByIdListTest(array(1))) && p() && e('0');
r($test->getScanIssueListByIdListTest(array(1, 2))) && p() && e('0');
r($test->getScanIssueListByIdListTest(array())) && p() && e('1');
r($test->getScanIssueListByIdListTest(array(100, 101, 102))) && p() && e('0');
r($test->getScanIssueListByIdListTest(array(0))) && p() && e('0');
