#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';
su('admin');

zenData('oauth')->gen(10);

/**

title=测试 webhookModel->getBindAccount();
timeout=0
cid=1

- 按条件查出openID=1的关联用户 @user3
- 按条件查出openID=空时，关联的用户 @0
- 按条件查出openID=8的关联用户 @user10
- 按条件查出openID=10的关联用户 @user12
- 按条件查出openID=3的关联用户 @user5

*/

$webhook = new webhookTest();

$ID     = array();
$ID[0]     = 1;
$ID[1]     = 2;
$ID[2]     = '';

$type   = array();
$type[0]   = 'gitlab';
$type[1]   = '';

$openID = array();
$openID[0] = 1;
$openID[1] = '';

$result1 = $webhook->getBindAccountTest(1, 'gitlab', 1);
$result2 = $webhook->getBindAccountTest(1, '', 1);
$result3 = $webhook->getBindAccountTest(1, 'gogs', 8);
$result4 = $webhook->getBindAccountTest(1, 'webhook', 10);
$result5 = $webhook->getBindAccountTest(1, 'gitea', 3);

r($result1) && p() && e('user3'); //按条件查出openID=1的关联用户
r($result2) && p() && e('0');     //按条件查出openID=空时，关联的用户
r($result3) && p() && e('user10'); //按条件查出openID=8的关联用户
r($result4) && p() && e('user12'); //按条件查出openID=10的关联用户
r($result5) && p() && e('user5'); //按条件查出openID=3的关联用户