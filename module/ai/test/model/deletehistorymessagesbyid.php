#!/usr/bin/env php
<?php

/**

title=测试 aiModel::deleteHistoryMessagesByID();
timeout=0
cid=15017

- 执行aiTest模块的deleteHistoryMessagesByIDTest方法，参数是1, 1, array  @0
- 执行aiTest模块的deleteHistoryMessagesByIDTest方法，参数是2, 2, array  @0
- 执行aiTest模块的deleteHistoryMessagesByIDTest方法，参数是999, 1, array  @0
- 执行aiTest模块的deleteHistoryMessagesByIDTest方法，参数是1, 999, array  @0
- 执行aiTest模块的deleteHistoryMessagesByIDTest方法，参数是1, 1, array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_message');
$table->id->range('1-20');
$table->appID->range('1{10},2{5},3{5}');
$table->user->range('1{8},2{6},3{6}');
$table->type->range('req{8},res{8},ntf{4}');
$table->content->range('测试消息内容1{5},测试消息内容2{5},测试消息内容3{5},测试消息内容4{5}');
$table->createdDate->range('`2024-09-01 10:00:00`');
$table->gen(20);

su('admin');

$aiTest = new aiTest();

r($aiTest->deleteHistoryMessagesByIDTest(1, 1, array(1, 2, 3))) && p() && e('0');
r($aiTest->deleteHistoryMessagesByIDTest(2, 2, array())) && p() && e('0');
r($aiTest->deleteHistoryMessagesByIDTest(999, 1, array(1, 2))) && p() && e('0');
r($aiTest->deleteHistoryMessagesByIDTest(1, 999, array(1, 2))) && p() && e('0');
r($aiTest->deleteHistoryMessagesByIDTest(1, 1, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10))) && p() && e('0');