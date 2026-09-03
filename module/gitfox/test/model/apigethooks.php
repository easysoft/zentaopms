#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigethooks();
timeout=0
cid=0

- 步骤 1：apiGetHooks 不产生 dao 错误 @0
- 步骤 2：apiGetHooks 返回值类型为 array 或 bool @1
- 步骤 3：apiGetHooks 查询结果标记为 0 或 1 @1
- 步骤 4：删除该 webhook 后查询不到它 @0
- 步骤 5：删除后 apiGetHooks 返回值类型为 array 或 bool @1

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
$hookURL    = 'http://example.com/list-hook-' . uniqid();
$hook       = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => $hookURL, 'displayName' => 'list-hook-' . uniqid()));
$hookID     = is_object($hook) && isset($hook->id) ? (int)$hook->id : 0;

r($gitfoxTest->apiGetHooksErrorTest(1)) && p() && e('0');
r(in_array($gitfoxTest->apiGetHooksTypeTest(1), array('array', 'bool'))) && p() && e('1');
r(in_array($gitfoxTest->apiGetHooksContainsUrlTest(1, $hookURL), array(0, 1))) && p() && e('1');
$gitfoxTest->apiDeleteWebhookTest(1, $hookID);
r($gitfoxTest->apiGetHooksContainsUrlTest(1, $hookURL)) && p() && e('0');
r(in_array($gitfoxTest->apiGetHooksTypeTest(1), array('array', 'bool'))) && p() && e('1');
