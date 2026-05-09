#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('webhook')->gen(10);
zenData('oauth')->gen(10);

/**

title=测试 webhookModel->getBoundUsers();
timeout=0
cid=19691

- 统计匹配数量 @0
- 统计匹配数量 @0
- 统计匹配数量 @0
- 统计匹配数量 @0
- 统计匹配数量 @0

*/

$webhook = new webhookModelTest();

r($webhook->getFeishuBoundOpenIdTest('user1')) && p() && e('0'); //统计匹配数量
r($webhook->getFeishuBoundOpenIdTest('user2')) && p() && e('0'); //统计匹配数量
r($webhook->getFeishuBoundOpenIdTest('user3')) && p() && e('0'); //统计匹配数量
r($webhook->getFeishuBoundOpenIdTest('user4')) && p() && e('0'); //统计匹配数量
r($webhook->getFeishuBoundOpenIdTest('user5')) && p() && e('0'); //统计匹配数量