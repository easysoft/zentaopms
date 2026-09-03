#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiCreateBranch();
timeout=0
cid=0

- 步骤 1：name 为空 apiCreateBranch 返回空对象 @0
- 步骤 2：source 为空 apiCreateBranch 返回空对象 @0
- 步骤 3：不存在的仓库调用 apiCreateBranch 不产生 dao 错误 @0
- 步骤 4：不存在的仓库返回 failure 和资源未找到 @failure,资源未找到。
- 步骤 5：不存在的仓库返回值类型为 object @object

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
$model = $gitfoxTest->instance;
$missingRepoID = 999999;

$emptyName  = (object)array('name' => '', 'source' => 'main');
$emptySource = (object)array('name' => 'test', 'source' => '');
$valid = (object)array('name' => 'feature/test', 'source' => 'main');

r($gitfoxTest->apiCreateBranchCountTest(1, $emptyName)) && p() && e('0');
r($gitfoxTest->apiCreateBranchCountTest(1, $emptySource)) && p() && e('0');
r($gitfoxTest->apiCreateBranchErrorTest($missingRepoID, $valid)) && p() && e('0');
r($gitfoxTest->apiCreateBranchTest($missingRepoID, $valid)) && p('code,message') && e('failure,资源未找到。');
r($gitfoxTest->apiCreateBranchResultTypeTest($missingRepoID, $valid)) && p() && e('object');
