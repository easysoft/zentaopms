#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::addPushWebhook();
timeout=0
cid=1

- 使用repoID为1，不存在的项目id推送webhook @0
- 使用repoID为1，存在的项目id推送webhook @1

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->gen(1);

$gitlab = new gitlabTest();

$repoID = 1;
$token  = '';
$_SERVER['REQUEST_URI'] = 'http://unittest/';

r($gitlab->addPushWebhookTest($repoID, $token))    && p() && e('0'); //使用repoID为1，不存在的项目id推送webhook
r($gitlab->addPushWebhookTest($repoID, $token, 2)) && p() && e('1'); //使用repoID为1，存在的项目id推送webhook