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

title=测试 codescanModel->getIssueTreeList();
timeout=0
cid=0

- 获取空仓库的文件树失败 @0
- 获取空仓库的规则树失败 @0
- 获取 1 号仓库的文件树根节点 @root
- 获取不存在任务的规则树失败 @0
- 获取不存在任务的文件树失败 @0

*/

su('admin');
$test = new codescanModelTest();

r($test->getIssueTreeListTest(0, 0, 'file')) && p() && e('0');
r($test->getIssueTreeListTest(0, 0, 'rule')) && p() && e('0');
r($test->getIssueTreeListTest(1, 0, 'file')) && p('0:name') && e('root');
r($test->getIssueTreeListTest(0, 1, 'rule')) && p() && e('0');
r($test->getIssueTreeListTest(2, 3, 'file')) && p() && e('0');
