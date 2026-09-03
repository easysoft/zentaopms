#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiDeleteWebhook();
timeout=0
cid=0

- 步骤 1：空 repoID 不产生 dao 错误 @0
- 步骤 2：空 repoID 返回 false @0
- 步骤 3：删除新建 webhook 返回 true @1
- 步骤 4：删除新建 webhook 的返回类型是 bool @bool
- 步骤 5：删除新建 webhook 不产生 dao 错误 @0

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
$hookOne    = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => 'http://example.com/delete-hook-' . uniqid(), 'displayName' => 'delete-hook-' . uniqid()));
$hookTwo    = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => 'http://example.com/delete-hook-' . uniqid(), 'displayName' => 'delete-hook-' . uniqid()));
$hookThree  = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => 'http://example.com/delete-hook-' . uniqid(), 'displayName' => 'delete-hook-' . uniqid()));

r($gitfoxTest->apiDeleteWebhookErrorTest(0, 5)) && p() && e('0');
r($gitfoxTest->apiDeleteWebhookTest(0, 5)) && p() && e('0');
r($gitfoxTest->apiDeleteWebhookTest(1, (int)$hookOne->id)) && p() && e('1');
r($gitfoxTest->apiDeleteWebhookTypeTest(1, (int)$hookTwo->id)) && p() && e('bool');
r($gitfoxTest->apiDeleteWebhookErrorTest(1, (int)$hookThree->id)) && p() && e('0');
