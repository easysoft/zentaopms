#!/usr/bin/env php
<?php

/**

title=测试 testcaseModel->processDateField();
timeout=0
cid=0

- 获取case 1 2 可以加入的数据
 - 属性createdDate @2024-01-29
 - 属性lastEditedDate @``
 - 属性lastRunDate @``

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');
$case1 = new stdclass();
$case1->id   = 1;
$case1->createdDate    = '2024-01-29';
$case1->lastEditedDate = '0000-00-00';
$case1->lastRunDate    = null;

global $tester;
$caseModel = $tester->loadModel('testcase');

r($caseModel->processDateField($case1)) && p('createdDate,lastEditedDate,lastRunDate')  && e('2024-01-29,``,``'); // 获取case 1 2 可以加入的数据