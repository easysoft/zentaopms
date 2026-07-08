#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::isWebhookExists();
timeout=0
cid=16662

- projectID=42, callbackURL匹配存在的webhook @1
- projectID=42, callbackURL不匹配任何webhook @0
- projectID=42, callbackURL为空 @0
- projectID=999, callbackURL匹配（但项目不存在webhook） @0
- projectID=42, callbackURL不匹配（带额外参数） @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitlabTest = new gitlabModelTest();

$url   = 'https://gitlab.example.com';
$token = 'test-token';

r($gitlabTest->isWebhookExistsTest($url, $token, '42', 'http://api.php/v1/gitlab/webhook?repoID=1')) && p() && e('1');
r($gitlabTest->isWebhookExistsTest($url, $token, '42', 'http://api.php/v1/gitlab/webhook?repoID=999')) && p() && e('0');
r($gitlabTest->isWebhookExistsTest($url, $token, '42', '')) && p() && e('0');
r($gitlabTest->isWebhookExistsTest($url, $token, '999', 'http://api.php/v1/gitlab/webhook?repoID=1')) && p() && e('0');
r($gitlabTest->isWebhookExistsTest($url, $token, '42', 'http://api.php/v1/gitlab/webhook?repoID=1&param=test%20value')) && p() && e('0');
