#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel->processDateField();
timeout=0
cid=19016

- 获取case 1 2 可以加入的数据
 - 属性createdDate @2024-01-29
 - 属性lastEditedDate @``
 - 属性lastRunDate @``
- 获取case 1 2 可以加入的数据
 - 属性createdDate @2025-01-01
 - 属性lastEditedDate @2025-01-01
 - 属性lastRunDate @2025-01-01

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');
$case1 = new stdclass();
$case1->id = 1;
$case1->createdDate    = '2024-01-29';
$case1->lastEditedDate = '0000-00-00';
$case1->lastRunDate    = null;

global $tester;
$caseModel = $tester->loadModel('testcase');

r($caseModel->processDateField($case1)) && p('createdDate,lastEditedDate,lastRunDate')  && e('2024-01-29,``,``'); // 获取case 1 2 可以加入的数据

$case2 = new stdclass();
$case2->id = 2;
$case2->createdDate    = '2025-01-01';
$case2->lastEditedDate = '2025-01-01';
$case2->lastRunDate    = '2025-01-01';

r($caseModel->processDateField($case2)) && p('createdDate,lastEditedDate,lastRunDate')  && e('2025-01-01,2025-01-01,2025-01-01'); // 获取case 1 2 可以加入的数据