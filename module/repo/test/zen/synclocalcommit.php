#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
zenData('entry')->loadYaml('entry')->gen(1);

/**

title=测试 repoZen->synclocalcommit();
timeout=0
cid=0

- 调用syncLocalCommitTest验证返回 @1
- 第二次调用返回一致 @1
- 第三次调用返回一致 @1
- 第四次调用返回一致 @1
- 第五次调用返回一致 @1

*/

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
$test = new repoZenTest();

r($test->syncLocalCommitAvailableTest(1)) && p() && e('1');
r($test->syncLocalCommitAvailableTest(1)) && p() && e('1');
r($test->syncLocalCommitAvailableTest(1)) && p() && e('1');
r($test->syncLocalCommitAvailableTest(1)) && p() && e('1');
r($test->syncLocalCommitAvailableTest(1)) && p() && e('1');
