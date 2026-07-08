#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::addPushWebhook();
timeout=0
cid=16572

- 使用pipelineID为1，不存在的项目id推送webhook @0
- 使用pipelineID为1，存在的项目id推送webhook @1
- 使用pipelineID为1，异常的项目id推送webhook @0
- 使用pipelineID为2，不存在的项目id推送webhook @0
- 使用pipelineID为2，存在的项目id推送webhook @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitlabTest = new gitlabModelTest();

$url           = 'https://gitlab.example.com';
$token         = 'test-token';
$callbackToken = '';

r($gitlabTest->addPushWebhookTest('1', $callbackToken, $url, $token))      && p() && e('0');
r($gitlabTest->addPushWebhookTest('1', $callbackToken, $url, $token, '2'))  && p() && e('1');
r($gitlabTest->addPushWebhookTest('1', $callbackToken, $url, $token, '-1')) && p() && e('0');
r($gitlabTest->addPushWebhookTest('2', $callbackToken, $url, $token))       && p() && e('0');
r($gitlabTest->addPushWebhookTest('2', $callbackToken, $url, $token, '2'))  && p() && e('1');
