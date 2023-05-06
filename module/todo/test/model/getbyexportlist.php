#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/todo.class.php';
su('admin');

/**

title=测试 todoModel->getByExportList();
cid=1
pid=1

*/

zdTable('todo')->config('getbyexportlist')->gen(5);

$todo = new todoTest();

$formData = new stdClass();
$formData->rawdata = new stdClass();
$formData->rawdata->exportType = '';
$testWhere  = " `deleted` = '0' AND `vision` = 'rnd' AND `assignedTo` = 'admin' AND `date` >= '20230301' AND `date` <= '20230301' AND `status` IN ('wait') ";
$testWhere2 = " `deleted` = '0' and `status` IN ('closed') ";

$testResult  = $todo->getByExportListTest("date_desc", $formData, $testWhere,  '1');
$testResult2 = $todo->getByExportListTest("date_desc", $formData, $testWhere2, '1');

r(count($testResult)) && p() && e('1');
r($testResult[1]) && p('name,status') && e('待办1,wait');

r(count($testResult2)) && p() && e('2');
r($testResult2[4]) && p('name,status') && e('待办4,closed');
r($testResult2[5]) && p('name,status') && e('待办5,closed');
