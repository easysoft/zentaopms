#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getServer();
timeout=0
cid=0

- 步骤 1：存在 GitFox 入口时 url 正确 @http://localhost:3001
- 步骤 2：存在 GitFox 入口时 token 匹配预期 @1
- 步骤 3：移除 GitFox 入口后 token 为空 @1
- 步骤 4：getServer 返回对象属性数为 2 @2
- 步骤 5：getServer 返回值类型为 object @object

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
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$gitfoxTest = new gitfoxModelTest();
$gitfoxTest->seedGitFoxEntry();

r($gitfoxTest->getServerTest()) && p('url') && e('http://localhost:3001');
r($gitfoxTest->getServerTokenMatchesTest('252f92b992f64597e84f910fd9135230')) && p() && e('1');
$gitfoxTest->clearGitFoxEntry();
r($gitfoxTest->getServerTokenEmptyTest()) && p() && e('1');
$gitfoxTest->seedGitFoxEntry();
r($gitfoxTest->getServerCountTest()) && p() && e('2');
r($gitfoxTest->getServerTypeTest()) && p() && e('object');
