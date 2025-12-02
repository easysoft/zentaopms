#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';
su('admin');

zenData('notify')->gen(50);

/**

title=测试 webhookModel->getDataList();
timeout=0
cid=19695

- 不需传任何参数,取出其中一个ID=40的日志内容，这里时间上取当前时间三小时以前的
 - 第40条的id属性 @40
 - 第40条的objectType属性 @webhook
 - 第40条的objectID属性 @0
 - 第40条的action属性 @0
 - 第40条的status属性 @wait
- 统计数量 @8

*/

$webhook = new webhookTest();

r($webhook->getDataListTest())        && p('40:id,objectType,objectID,action,status') && e('40,webhook,0,0,wait'); // 不需传任何参数,取出其中一个ID=40的日志内容，这里时间上取当前时间三小时以前的
r(count($webhook->getDataListTest())) && p()                                          && e('8');                   // 统计数量
