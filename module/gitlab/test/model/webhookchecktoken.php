#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->apiGetJobs();
timeout=0
cid=1

- 使用错误的token检查 @Token error.
- 使用正确的token检查 @0

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$_GET['gitlab']                 = 1;

$_SERVER['HTTP_X_GITLAB_TOKEN'] = '';
r($gitlab->webhookCheckTokenTest()) && p() && e('Token error.'); //使用错误的token检查
$_SERVER['HTTP_X_GITLAB_TOKEN'] = '08bcc98f75d7d40053dc80722bdc117b';
r($gitlab->webhookCheckTokenTest()) && p() && e('0'); //使用正确的token检查