#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetWebhookExecution();
timeout=0
cid=0

- 步骤 1：查询 webhook 执行列表不产生 dao 错误 @0
- 步骤 2：查询 webhook 执行列表返回值类型为 array @array
- 步骤 3：新建 webhook 的执行列表为空 @0
- 步骤 4：查询不存在的单条执行记录会产生 dao 错误 @1
- 步骤 5：查询不存在的单条执行记录返回 false @0

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
$hook       = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => 'http://example.com/execution-hook-' . uniqid(), 'displayName' => 'execution-hook-' . uniqid()));

r($gitfoxTest->apiGetWebhookExecutionErrorTest(1, (int)$hook->id)) && p() && e('0');
r($gitfoxTest->apiGetWebhookExecutionTypeTest(1, (int)$hook->id)) && p() && e('array');
r($gitfoxTest->apiGetWebhookExecutionCountTest(1, (int)$hook->id)) && p() && e('0');
r($gitfoxTest->apiGetWebhookExecutionErrorTest(1, (int)$hook->id, 1)) && p() && e('1');
r($gitfoxTest->apiGetWebhookExecutionTest(1, (int)$hook->id, 1)) && p() && e('0');
