#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

zenData('testtask')->gen(5);

su('admin');

/**

title=测试 testtaskModel->updateStatus();
cid=19221

- 测试更新测试单 ID 为 1 的状态到doing属性status @doing
- 测试更新测试单 ID 为 2 的状态到doing属性status @doing
- 测试更新测试单 ID 为 3 的状态到doing属性status @doing
- 测试更新测试单 ID 为 4 的状态到doing属性status @doing
- 测试更新测试单 ID 为 5 的状态到doing属性status @doing
- 测试更新测试单 ID 不存在的 10001 的状态到wait属性status @0
- 测试更新测试单 ID 不存在的 0 的状态到wait属性status @0

*/

$uid = uniqid();

$taskIdList = array(1, 2, 3, 4, 5, 10001, 0);

$testtask = new testtaskTest();

r($testtask->updateStatusTest($taskIdList[0])) && p('status') && e('doing'); // 测试更新测试单 ID 为 1 的状态到doing
r($testtask->updateStatusTest($taskIdList[1])) && p('status') && e('doing'); // 测试更新测试单 ID 为 2 的状态到doing
r($testtask->updateStatusTest($taskIdList[2])) && p('status') && e('doing'); // 测试更新测试单 ID 为 3 的状态到doing
r($testtask->updateStatusTest($taskIdList[3])) && p('status') && e('doing'); // 测试更新测试单 ID 为 4 的状态到doing
r($testtask->updateStatusTest($taskIdList[4])) && p('status') && e('doing'); // 测试更新测试单 ID 为 5 的状态到doing
r($testtask->updateStatusTest($taskIdList[5])) && p('status') && e('0');     // 测试更新测试单 ID 不存在的 10001 的状态到wait
r($testtask->updateStatusTest($taskIdList[6])) && p('status') && e('0');     // 测试更新测试单 ID 不存在的 0 的状态到wait
