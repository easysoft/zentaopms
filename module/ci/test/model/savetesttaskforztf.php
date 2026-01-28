#!/usr/bin/env php
<?php

/**

title=测试 ciModel->saveTestTaskForZtf();
timeout=0
cid=15590

- 空数据返回ID为5的测试单
 - 属性id @5
 - 属性name @测试单5
- 不存在的测试单 @0
- 正常的测试单
 - 属性name @测试单1
 - 属性auto @unit
- 有产品ID，创建测试单
 - 属性id @6
 - 属性auto @unit
- 有产品ID，自定义测试单名称，创建测试单
 - 属性id @7
 - 属性auto @unit
 - 属性name @单测创建的测试单

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('pipeline')->gen(3);
zenData('testtask')->gen(5);
zenData('project')->gen(5);
zenData('projectproduct')->gen(5);
zenData('compile')->loadYaml('compile')->gen(1);
su('admin');

$productID = 0;
$taskID    = 0;
$name      = '';

$ci = new ciModelTest();
r($ci->saveTestTaskForZtfTest($productID, $taskID, $name)) && p('id,name') && e('5,测试单5'); // 空数据返回ID为5的测试单

$taskID = 10;
r($ci->saveTestTaskForZtfTest($productID, $taskID, $name)) && p() && e('0'); // 不存在的测试单

$taskID = 1;
r($ci->saveTestTaskForZtfTest($productID, $taskID, $name)) && p('name,auto') && e('测试单1,unit'); // 正常的测试单

$taskID    = 0;
$productID = 1;
r($ci->saveTestTaskForZtfTest($productID, $taskID, $name)) && p('id,auto') && e('6,unit'); // 有产品ID，创建测试单

$name = '单测创建的测试单';
r($ci->saveTestTaskForZtfTest($productID, $taskID, $name)) && p('id,auto,name') && e('7,unit,单测创建的测试单'); // 有产品ID，自定义测试单名称，创建测试单