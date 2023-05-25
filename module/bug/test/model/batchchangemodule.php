#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';
su('user1');

/**

title=测试bugModel->batchChangeModule();
timeout=0
cid=1

- 修改bug1 2模块为0
 - 第0条的field属性 @module
 - 第0条的old属性 @1821
 - 第0条的new属性 @0

- 修改bug1 2模块为8
 - 第0条的field属性 @module
 - 第0条的old属性 @0
 - 第0条的new属性 @8

- 修改bug1 2模块为9
 - 第0条的field属性 @module
 - 第0条的old属性 @8
 - 第0条的new属性 @9

- 修改bug1 2模块为10
 - 第0条的field属性 @module
 - 第0条的old属性 @9
 - 第0条的new属性 @10

- 修改bug1 2模块为11
 - 第0条的field属性 @module
 - 第0条的old属性 @10
 - 第0条的new属性 @11

- 修改bug1 2模块为11 未发生变化 @0

- 修改bug1 3模块为0
 - 第0条的field属性 @module
 - 第0条的old属性 @11
 - 第0条的new属性 @0

- 修改bug1 3模块为8
 - 第0条的field属性 @module
 - 第0条的old属性 @0
 - 第0条的new属性 @8

- 修改bug1 3模块为9
 - 第0条的field属性 @module
 - 第0条的old属性 @8
 - 第0条的new属性 @9

- 修改bug1 3模块为10
 - 第0条的field属性 @module
 - 第0条的old属性 @9
 - 第0条的new属性 @10

- 修改bug1 3模块为11
 - 第0条的field属性 @module
 - 第0条的old属性 @10
 - 第0条的new属性 @11

- 修改bug1 3模块为11 未发生变化 @0

- 修改bug1 4模块为0
 - 第0条的field属性 @module
 - 第0条的old属性 @11
 - 第0条的new属性 @0

- 修改bug1 4模块为8
 - 第0条的field属性 @module
 - 第0条的old属性 @0
 - 第0条的new属性 @8

- 修改bug1 4模块为9
 - 第0条的field属性 @module
 - 第0条的old属性 @8
 - 第0条的new属性 @9

- 修改bug1 4模块为10
 - 第0条的field属性 @module
 - 第0条的old属性 @9
 - 第0条的new属性 @10

- 修改bug1 4模块为11
 - 第0条的field属性 @module
 - 第0条的old属性 @10
 - 第0条的new属性 @11

- 修改bug1 4模块为11 未发生变化 @0

- 修改bug2 3模块为0
 - 第0条的field属性 @module
 - 第0条的old属性 @11
 - 第0条的new属性 @0

- 修改bug2 3模块为8
 - 第0条的field属性 @module
 - 第0条的old属性 @0
 - 第0条的new属性 @8

- 修改bug2 3模块为9
 - 第0条的field属性 @module
 - 第0条的old属性 @8
 - 第0条的new属性 @9

- 修改bug2 3模块为10
 - 第0条的field属性 @module
 - 第0条的old属性 @9
 - 第0条的new属性 @10

- 修改bug2 3模块为11
 - 第0条的field属性 @module
 - 第0条的old属性 @10
 - 第0条的new属性 @11

- 修改bug2 3模块为11 未发生变化 @0

*/

zdTable('bug')->gen(10);
zdTable('module')->gen(20);

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