#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::isWebhookExists();
timeout=0
cid=0

- 步骤 1：isWebhookExists 不产生 dao 错误 @0
- 步骤 2：isWebhookExists 返回 bool @1
- 步骤 3：isWebhookExists 返回值非 null @1
- 步骤 4：isWebhookExists 可重复调用 @1
- 步骤 5：isWebhookExists 空 URL 正常 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;

$repo = (object)array('id' => 1, 'name' => 'test');

r((int)dao::isError()) && p() && e('0');
ob_start();
$r = $model->isWebhookExists($repo, 'http://test.com/hook');
ob_end_clean();
r(is_bool($r)) && p() && e('1');
r(!is_null($r)) && p() && e('1');
ob_start();
$r = $model->isWebhookExists($repo);
ob_end_clean();
r(is_bool($r)) && p() && e('1');
ob_start();
$r = $model->isWebhookExists($repo, 'http://test.com/hook');
ob_end_clean();
r(is_bool($r)) && p() && e('1');
