#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('log')->gen(10);

/**

title=测试 webhookModel->getLogList();
timeout=0
cid=1

- 统计ID为1的日志数量 @0
- 统计ID为2的日志数量 @1
- 统计ID不存在时的数量 @0
- 取出ID为1的其中一个匹配操作内容第4条的action属性 @0

*/

$webhook = new webhookTest();

$ID = array();
$ID[0] = 1;
$ID[1] = 3;
$ID[2] = 1111;

$result1 = $webhook->getLogListTest($ID[0]);
$result2 = $webhook->getLogListTest($ID[1]);
$result3 = $webhook->getLogListTest($ID[2]);

//a($result1);die;
r(count($result1)) && p()           && e('0'); //统计ID为1的日志数量
r(count($result2)) && p()           && e('1'); //统计ID为2的日志数量
r(count($result3)) && p()           && e('0'); //统计ID不存在时的数量
r($result1)        && p('4:action') && e('0'); //取出ID为1的其中一个匹配操作内容