#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::isWebhookExists();
timeout=0
cid=1

- 使用repoID为1，不存在的项目id推送webhook @0
- 使用repoID为1，存在的项目id推送webhook @1

*/

zdTable('pipeline')->gen(5);
zdTable('repo')->gen(1);

$gitlab = new gitlabTest();

$repoID = 1;
$url    = 'http:/api.php/v1/gitlab/webhook?repoID=1';

r($gitlab->isWebhookExistsTest($repoID, ''))   && p() && e('0'); //检查url为空的webhook是否存在
r($gitlab->isWebhookExistsTest($repoID, $url)) && p() && e('1'); //用正常的url检查webhook是否存在
