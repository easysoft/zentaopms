#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('build')->loadYaml('build')->gen(100);

/**

title=bugModel->getLinkedExecutionByIdList();
timeout=0
cid=15384

- 测试获取版本ID 1 3 5 的执行 @1,3

- 测试获取版本ID 2 4 6 的执行 @2,4

- 测试获取版本ID 41 51 61 的执行 @39,49,59,2

- 测试获取版本ID 10 30 70 的执行 @8,28,68,2

- 测试获取版本ID 不存在 的执行 @0

*/

$builds = array('', '1,3,5', '2,4,6', '41,51,61', '10,30,70', '1000001,100002');

$bug=new bugTest();
r($bug->getLinkedExecutionByIdListTest($builds[1])) && p() && e('1,3');        // 测试获取版本ID 1 3 5 的执行
r($bug->getLinkedExecutionByIdListTest($builds[2])) && p() && e('2,4');        // 测试获取版本ID 2 4 6 的执行
r($bug->getLinkedExecutionByIdListTest($builds[3])) && p() && e('39,49,59,2'); // 测试获取版本ID 41 51 61 的执行
r($bug->getLinkedExecutionByIdListTest($builds[4])) && p() && e('8,28,68,2');  // 测试获取版本ID 10 30 70 的执行
r($bug->getLinkedExecutionByIdListTest($builds[5])) && p() && e('0');          // 测试获取版本ID 不存在 的执行
