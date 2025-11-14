#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';
su('admin');

/**

title=测试 gitlabModel::addPushWebhook();
timeout=0
cid=16572

- 使用repoID为1，不存在的项目id推送webhook @0
- 使用repoID为1，存在的项目id推送webhook @1
- 使用repoID为1，异常的项目id推送webhook @1
- 使用repoID为2，不存在的项目id推送webhook @0
- 使用repoID为2，存在的项目id推送webhook @1

*/

zenData('pipeline')->gen(5);
zenData('repo')->gen(2);

$gitlab = new gitlabTest();

$token  = '';
$_SERVER['REQUEST_URI'] = 'http://unittest/';

r($gitlab->addPushWebhookTest(1, $token))     && p() && e('0'); //使用repoID为1，不存在的项目id推送webhook
r($gitlab->addPushWebhookTest(1, $token, 2))  && p() && e('1'); //使用repoID为1，存在的项目id推送webhook
r($gitlab->addPushWebhookTest(1, $token, -1)) && p() && e('0'); //使用repoID为1，异常的项目id推送webhook

r($gitlab->addPushWebhookTest(2, $token))    && p() && e('0'); //使用repoID为2，不存在的项目id推送webhook
r($gitlab->addPushWebhookTest(2, $token, 2)) && p() && e('1'); //使用repoID为2，存在的项目id推送webhook