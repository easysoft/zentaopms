#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::addPushWebhook();
timeout=0
cid=0

- 步骤 1：addPushWebhook 不产生 dao 错误 @0
- 步骤 2：addPushWebhook 返回 true 或 array @1
- 步骤 3：addPushWebhook 返回 bool 或 array 类型 @1
- 步骤 4：addPushWebhook 可重复调用 @1
- 步骤 5：addPushWebhook 再执行不崩溃 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');
$_SERVER['REQUEST_URI'] = '/zentao/gitfox-browse.html';

$gitfoxTest = new gitfoxModelTest();

$repo = (object)array('id' => 1, 'name' => 'test');

r($gitfoxTest->addPushWebhookErrorTest($repo)) && p() && e('0');
r($gitfoxTest->addPushWebhookTest($repo)) && p() && e('1');
r($gitfoxTest->addPushWebhookTypeTest($repo)) && p() && e('bool');
r($gitfoxTest->addPushWebhookTest($repo)) && p() && e('1');
r($gitfoxTest->addPushWebhookTest($repo, 'secret-token')) && p() && e('1');
