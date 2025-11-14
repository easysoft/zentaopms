#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::getLogList();
timeout=0
cid=19699

- 执行webhook模块的getLogListTest方法，参数是1  @9
- 执行webhook模块的getLogListTest方法，参数是999  @1
- 执行webhook模块的getLogListTest方法，参数是1, 'id_asc' 第1条的id属性 @1
- 执行webhook模块的getLogListTest方法，参数是2 第10条的action属性 @10
- 执行webhook模块的getLogListTest方法，参数是3  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';

zenData('user')->gen(10);

$logTable = zenData('log');
$logTable->id->range('1-15');
$logTable->objectType->range('webhook{15}');
$logTable->objectID->range('1{9},2{3},3{2},999{1}');
$logTable->action->range('1-15');
$logTable->date->range('`2024-01-01 10:00:00`,`2024-01-02 11:00:00`,`2024-01-03 12:00:00`');
$logTable->url->range('http://test1.com,http://test2.com,http://test3.com');
$logTable->contentType->range('application/json{10},text/plain{5}');
$logTable->data->range('{"msg":"test1"},{"msg":"test2"},{"msg":"test3"}');
$logTable->result->range('success{10},failed{5}');
$logTable->gen(15);

zenData('action')->gen(15);
zenData('story')->gen(50);
zenData('task')->gen(50);

su('admin');

$webhook = new webhookTest();

r(count($webhook->getLogListTest(1))) && p() && e('9');
r(count($webhook->getLogListTest(999))) && p() && e('1');
r($webhook->getLogListTest(1, 'id_asc')) && p('1:id') && e('1');
r($webhook->getLogListTest(2)) && p('10:action') && e('10');
r(count($webhook->getLogListTest(3))) && p() && e('2');