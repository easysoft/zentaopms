#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getDataList();
cid=1
pid=1

不需传任何参数,取出其中一个ID=39的日志内容，这里时间上取当前时间三小时以前的 >> 用户创建了任务39
统计数量 >> 20

*/

$webhook = new webhookTest();

r($webhook->getDataListTest())        && p('39:data') && e('用户创建了任务39'); //不需传任何参数,取出其中一个ID=39的日志内容，这里时间上取当前时间三小时以前的
r(count($webhook->getDataListTest())) && p()          && e('20');               //统计数量