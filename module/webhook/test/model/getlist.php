#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';
su('admin');

zenData('webhook')->gen(10);

/**

title=测试 webhookModel->getList();
timeout=0
cid=1

- 统计获取list里元素数量 @10
- 取出其中一个id第1条的id属性 @1
- 取出2的name第2条的name属性 @钉钉工作消息
- 取出3的name第3条的name属性 @企业微信机器人
- 取出4的createdBy第4条的createdBy属性 @admin

*/

$webhook = new webhookTest();

$orderBy = array();
$orderBy[0] = 'id_desc';
$orderBy[1] = '';

$pager = array();
$pager[0] = null;

$decode = array();
$decode[0] = true;
$decode[1] = '';

$result1 = $webhook->getListTest($orderBy[0]);

r(count($result1)) && p()              && e('10'); //统计获取list里元素数量
r($result1)        && p('1:id')        && e('1');  //取出其中一个id
r($result1)        && p('2:name')      && e('钉钉工作消息');  //取出2的name
r($result1)        && p('3:name')      && e('企业微信机器人');  //取出3的name
r($result1)        && p('4:createdBy') && e('admin');  //取出4的createdBy