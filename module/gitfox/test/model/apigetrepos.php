#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetRepos();
timeout=0
cid=0

- 步骤 1：默认查询 apiGetRepos 不产生 dao 错误 @0
- 步骤 2：默认查询 apiGetRepos 返回值类型为 array @array
- 步骤 3：不存在的仓库关键字不产生 dao 错误 @0
- 步骤 4：不存在的仓库关键字返回 0 条记录 @0
- 步骤 5：带关键字查询时返回值类型仍为 array @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
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

$gitfoxTest = new gitfoxModelTest();
$missingRepo = 'missing-repo-' . uniqid();

r($gitfoxTest->apiGetReposErrorTest()) && p() && e('0');
r($gitfoxTest->apiGetReposTypeTest()) && p() && e('array');
r($gitfoxTest->apiGetReposErrorTest($missingRepo)) && p() && e('0');
r($gitfoxTest->apiGetReposCountTest($missingRepo)) && p() && e('0');
r($gitfoxTest->apiGetReposTypeTest($missingRepo)) && p() && e('array');
