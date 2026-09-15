#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getWebhookByURL();
timeout=0
cid=0

- 步骤 1：getWebhookByURL 不产生 dao 错误 @0
- 步骤 2：存在的 URL 返回对应 webhook 对象 @1
- 步骤 3：getWebhookByURL 返回值类型为 object @object
- 步骤 4：不存在的 URL 返回 false @0
- 步骤 5：空 URL 返回 false @0
- 步骤 6：删除 webhook 后查不到 @0

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
$hookURL    = 'http://example.com/get-hook-by-url-' . uniqid();
$hook       = $gitfoxTest->apiCreateHookTest(1, (object)array('url' => $hookURL, 'displayName' => 'get-hook-by-url-' . uniqid()));
$hookID     = is_object($hook) && isset($hook->id) ? (int)$hook->id : 0;

r($gitfoxTest->getWebhookByURLErrorTest(1, $hookURL)) && p() && e('0'); // 步骤 1
r($gitfoxTest->getWebhookByURLUrlMatchesTest(1, $hookURL)) && p() && e('1'); // 步骤 2
r($gitfoxTest->getWebhookByURLTypeTest(1, $hookURL)) && p() && e('object'); // 步骤 3
r($gitfoxTest->getWebhookByURLTest(1, 'http://example.com/not-exist-hook')) && p() && e('0'); // 步骤 4
r($gitfoxTest->getWebhookByURLTest(1, '')) && p() && e('0'); // 步骤 5
$gitfoxTest->apiDeleteWebhookTest(1, $hookID);
r($gitfoxTest->getWebhookByURLTest(1, $hookURL)) && p() && e('0'); // 步骤 6
