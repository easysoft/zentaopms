#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::addPushWebhook();
timeout=0
cid=0

- 步骤 1：addPushWebhook 不产生 dao 错误 @0
- 步骤 2：addPushWebhook 返回 true 或 array @1
- 步骤 3：addPushWebhook 返回 bool 或 array 类型 @bool
- 步骤 4：addPushWebhook 可重复调用 @1
- 步骤 5：addPushWebhook 再执行不崩溃 @1

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
$_SERVER['REQUEST_URI'] = '/zentao/gitfox-browse.html';

$gitfoxTest = new gitfoxModelTest();

$repo = (object)array('id' => 1, 'name' => 'test', 'gitUID' => 'unit-test-git-uid');

r($gitfoxTest->addPushWebhookErrorTest($repo)) && p() && e('0');
r($gitfoxTest->addPushWebhookTest($repo)) && p() && e('1');
r($gitfoxTest->addPushWebhookTypeTest($repo)) && p() && e('bool');
r($gitfoxTest->addPushWebhookTest($repo)) && p() && e('1');
r($gitfoxTest->addPushWebhookTest($repo, 'secret-token')) && p() && e('1');
