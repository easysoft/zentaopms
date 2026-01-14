#!/usr/bin/env php
<?php
declare(strict_types=1);

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

/**

title=测试 todoModel->getByExportList();
timeout=0
cid=19256

- 获取待办的数量 @1
- 获取id为1的待办name和status
 - 属性name @待办1
 - 属性status @wait
- 获取导出待办的数量 @2
- 获取id为1的待办name和status
 - 属性name @待办4
 - 属性status @closed
- 获取id为5的待办name和status
 - 属性name @待办5
 - 属性status @closed

*/

zenData('todo')->loadYaml('getbyexportlist')->gen(5);

$todo = new todoModelTest();

$testWhere  = " `deleted` = '0' AND `vision` = 'rnd' AND `assignedTo` = 'admin' AND `date` >= '20230301' AND `date` <= '20230301' AND `status` IN ('wait') ";
$testWhere2 = " `deleted` = '0' and `status` IN ('closed') ";

$testResult  = $todo->getByExportListTest("date_desc", $testWhere,  $selectedItem = '');
$testResult2 = $todo->getByExportListTest("date_desc", $testWhere2, $selectedItem = '');

r(count($testResult)) && p() && e('1'); // 获取待办的数量
r($testResult[1]) && p('name,status') && e('待办1,wait'); // 获取id为1的待办name和status

r(count($testResult2)) && p() && e('2'); // 获取导出待办的数量
r($testResult2[4]) && p('name,status') && e('待办4,closed'); // 获取id为1的待办name和status
r($testResult2[5]) && p('name,status') && e('待办5,closed'); // 获取id为5的待办name和status
