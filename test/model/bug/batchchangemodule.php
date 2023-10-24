#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=测试bugModel->batchChangeModule();
cid=1
pid=1

修改bug1 2模块为0 >> module,1821,0
修改bug1 2模块为8 >> module,0,8
修改bug1 2模块为9 >> module,8,9
修改bug1 2模块为10 >> module,9,10
修改bug1 2模块为11 >> module,10,11
修改bug1 2模块为11 未发生变化 >> 0
修改bug1 3模块为0 >> module,11,0
修改bug1 3模块为8 >> module,0,8
修改bug1 3模块为9 >> module,8,9
修改bug1 3模块为10 >> module,9,10
修改bug1 3模块为11 >> module,10,11
修改bug1 3模块为11 未发生变化 >> 0
修改bug1 4模块为0 >> module,11,0
修改bug1 4模块为8 >> module,0,8
修改bug1 4模块为9 >> module,8,9
修改bug1 4模块为10 >> module,9,10
修改bug1 4模块为11 >> module,10,11
修改bug1 4模块为11 未发生变化 >> 0
修改bug2 3模块为0 >> module,11,0
修改bug2 3模块为8 >> module,0,8
修改bug2 3模块为9 >> module,8,9
修改bug2 3模块为10 >> module,9,10
修改bug2 3模块为11 >> module,10,11
修改bug2 3模块为11 未发生变化 >> 0

*/

$bugIDList1 = array('1', '2');
$bugIDList2 = array('1', '3');
$bugIDList3 = array('1', '4');
$bugIDList4 = array('2', '3');

$moduleList = array('0', '8', '9', '10', '11');

$bug = new bugTest();
r($bug->batchChangeModuleTest($bugIDList1, $moduleList[0], $bugIDList1[0])) && p('0:field,old,new') && e('module,1821,0');// 修改bug1 2模块为0
r($bug->batchChangeModuleTest($bugIDList1, $moduleList[1], $bugIDList1[1])) && p('0:field,old,new') && e('module,0,8');   // 修改bug1 2模块为8
r($bug->batchChangeModuleTest($bugIDList1, $moduleList[2], $bugIDList1[0])) && p('0:field,old,new') && e('module,8,9');   // 修改bug1 2模块为9
r($bug->batchChangeModuleTest($bugIDList1, $moduleList[3], $bugIDList1[1])) && p('0:field,old,new') && e('module,9,10');  // 修改bug1 2模块为10
r($bug->batchChangeModuleTest($bugIDList1, $moduleList[4], $bugIDList1[0])) && p('0:field,old,new') && e('module,10,11'); // 修改bug1 2模块为11
r($bug->batchChangeModuleTest($bugIDList1, $moduleList[4], $bugIDList1[1])) && p()                  && e('0');            // 修改bug1 2模块为11 未发生变化
r($bug->batchChangeModuleTest($bugIDList2, $moduleList[0], $bugIDList2[0])) && p('0:field,old,new') && e('module,11,0');  // 修改bug1 3模块为0
r($bug->batchChangeModuleTest($bugIDList2, $moduleList[1], $bugIDList2[1])) && p('0:field,old,new') && e('module,0,8');   // 修改bug1 3模块为8
r($bug->batchChangeModuleTest($bugIDList2, $moduleList[2], $bugIDList2[0])) && p('0:field,old,new') && e('module,8,9');   // 修改bug1 3模块为9
r($bug->batchChangeModuleTest($bugIDList2, $moduleList[3], $bugIDList2[1])) && p('0:field,old,new') && e('module,9,10');  // 修改bug1 3模块为10
r($bug->batchChangeModuleTest($bugIDList2, $moduleList[4], $bugIDList2[0])) && p('0:field,old,new') && e('module,10,11'); // 修改bug1 3模块为11
r($bug->batchChangeModuleTest($bugIDList2, $moduleList[4], $bugIDList2[1])) && p()                  && e('0');            // 修改bug1 3模块为11 未发生变化
r($bug->batchChangeModuleTest($bugIDList3, $moduleList[0], $bugIDList3[0])) && p('0:field,old,new') && e('module,11,0');  // 修改bug1 4模块为0
r($bug->batchChangeModuleTest($bugIDList3, $moduleList[1], $bugIDList3[1])) && p('0:field,old,new') && e('module,0,8');   // 修改bug1 4模块为8
r($bug->batchChangeModuleTest($bugIDList3, $moduleList[2], $bugIDList3[0])) && p('0:field,old,new') && e('module,8,9');   // 修改bug1 4模块为9
r($bug->batchChangeModuleTest($bugIDList3, $moduleList[3], $bugIDList3[1])) && p('0:field,old,new') && e('module,9,10');  // 修改bug1 4模块为10
r($bug->batchChangeModuleTest($bugIDList3, $moduleList[4], $bugIDList3[0])) && p('0:field,old,new') && e('module,10,11'); // 修改bug1 4模块为11
r($bug->batchChangeModuleTest($bugIDList3, $moduleList[4], $bugIDList3[1])) && p()                  && e('0');            // 修改bug1 4模块为11 未发生变化
r($bug->batchChangeModuleTest($bugIDList4, $moduleList[0], $bugIDList4[0])) && p('0:field,old,new') && e('module,11,0');  // 修改bug2 3模块为0
r($bug->batchChangeModuleTest($bugIDList4, $moduleList[1], $bugIDList4[1])) && p('0:field,old,new') && e('module,0,8');   // 修改bug2 3模块为8
r($bug->batchChangeModuleTest($bugIDList4, $moduleList[2], $bugIDList4[0])) && p('0:field,old,new') && e('module,8,9');   // 修改bug2 3模块为9
r($bug->batchChangeModuleTest($bugIDList4, $moduleList[3], $bugIDList4[1])) && p('0:field,old,new') && e('module,9,10');  // 修改bug2 3模块为10
r($bug->batchChangeModuleTest($bugIDList4, $moduleList[4], $bugIDList4[0])) && p('0:field,old,new') && e('module,10,11'); // 修改bug2 3模块为11
r($bug->batchChangeModuleTest($bugIDList4, $moduleList[4], $bugIDList4[1])) && p()                  && e('0');            // 修改bug2 3模块为11 未发生变化
