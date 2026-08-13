#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::isWebhookExists();
timeout=0
cid=0

- 步骤 1：isWebhookExists 不产生 dao 错误 @0
- 步骤 2：已存在的 webhook URL 返回 true @1
- 步骤 3：isWebhookExists 返回值类型为 bool @bool
- 步骤 4：不存在的 webhook URL 返回 false @0
- 步骤 5：空 URL 返回 false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');
$_SERVER['REQUEST_URI'] = '/zentao/gitfox-browse.html';

$gitfoxTest = new gitfoxModelTest();
$repo       = (object)array('id' => 1, 'name' => 'test');
$hookURL    = 'http://example.com/exist-hook-' . uniqid();
$gitfoxTest->apiCreateHookTest(1, (object)array('url' => $hookURL, 'displayName' => 'exist-hook-' . uniqid()));

r($gitfoxTest->isWebhookExistsErrorTest($repo, $hookURL)) && p() && e('0');
r($gitfoxTest->isWebhookExistsTest($repo, $hookURL)) && p() && e('1');
r($gitfoxTest->isWebhookExistsTypeTest($repo, $hookURL)) && p() && e('bool');
r($gitfoxTest->isWebhookExistsTest($repo, 'http://test.com/hook')) && p() && e('0');
r($gitfoxTest->isWebhookExistsTest($repo)) && p() && e('0');
