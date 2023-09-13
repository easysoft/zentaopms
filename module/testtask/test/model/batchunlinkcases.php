#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testtask.class.php';

zdTable('testrun')->config('testrun')->gen(6);

su('admin');

/**

title=测试 testtaskModel->batchUnlinkCases();
cid=1
pid=1

*/

$testtask = new testtaskTest();

r($testtask->batchUnlinkCasesTest(0, array(1))) && p() && e(0); // 测试单参数为 0 返回 false。
r($testtask->batchUnlinkCasesTest(1, array()))  && p() && e(0); // 测试用例参数为空返回 false。
r($testtask->batchUnlinkCasesTest(1, array(7))) && p() && e(0); // 测试用例参数没有关联到测试单返回 false。
r($testtask->batchUnlinkCasesTest(2, array(1))) && p() && e(0); // 测试单不存在返回 false。

r($testtask->batchUnlinkCasesTest(1, array(1, 2))) && p('cases;actions[0]:objectType|objectID|action|extra;actions[1]:objectType|objectID|action|extra', '|') && e('3,4,5,6;case|2|unlinkedfromtesttask|1;case|1|unlinkedfromtesttask|1'); // 从测试单 1 中移除用例 1 和用例 2，并记录日志。
r($testtask->batchUnlinkCasesTest(1, array(3, 4))) && p('cases;actions[0]:objectType|objectID|action|extra;actions[1]:objectType|objectID|action|extra', '|') && e('5,6;case|4|unlinkedfromtesttask|1;case|3|unlinkedfromtesttask|1');     // 从测试单 1 中移除用例 3 和用例 4，并记录日志。
