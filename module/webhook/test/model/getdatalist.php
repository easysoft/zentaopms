#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('notify')->gen(50);

/**

title=测试 webhookModel->getDataList();
timeout=0
cid=1

- 不需传任何参数,取出其中一个ID=40的日志内容，这里时间上取当前时间三小时以前的第40条的data属性 @用户创建了任务40
- 统计数量 @8

*/

$webhook = new webhookTest();

r($webhook->getDataListTest())        && p('40:data') && e('用户创建了任务40'); //不需传任何参数,取出其中一个ID=40的日志内容，这里时间上取当前时间三小时以前的
r(count($webhook->getDataListTest())) && p()          && e('8');                //统计数量