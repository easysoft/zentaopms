#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('todo')->loadYaml('batchcreate')->gen(5);

/**

title=测试 todoModel->batchCreate();
timeout=0
cid=19247

- 判断批量创建8条待办，返回创建成功的数量 @8
- 判断批量创建8条待办，返回创建成功的ID @6
- 判断批量创建8条待办，返回创建成功的ID属性1 @7
- 判断批量创建8条待办，返回创建成功的ID属性2 @8
- 判断批量创建8条待办，返回创建成功的ID属性3 @9

*/

$today       = date('Y-m-d');
$names       = array('批量创建待办1', '批量创建待办2', '批量创建待办3', '批量创建待办4', '批量创建待办5', '批量创建待办6', '批量创建待办7', '批量创建待办8');
$types       = array('custom', 'custom', 'custom', 'custom', 'custom', 'custom', 'custom', 'custom');
$pris        = array(1, 2, 3, 4, 1, 2, 3, 4);
$descs       = array('desc1', 'desc2', 'desc3', 'desc4', '' , '', '', '');
$begins      = array('0830', '0900', '0930', '1000', '1030', '1100', '1130', '1200');
$ends        = array('0900', '0930', '1000','1030', '1100', '1130', '1200', '1230');
$assignedTos = array('admin', 'productManager', 'projectManager', 'dev1', 'dev2', 'dev3', 'tester1', 'tester2',);
$todos       = array('types' => $types, 'pris' => $pris, 'names' => $names, 'descs' => $descs, 'begins' => $begins, 'ends' => $ends, 'assignedTos' => $assignedTos, 'date' => $today, 'switchDate' => '');

$todo = new todoModelTest();
$result = $todo->batchCreateTest($todos);
r(count($result)) && p()    && e('8');  // 判断批量创建8条待办，返回创建成功的数量
r($result)        && p('0') && e('6');  // 判断批量创建8条待办，返回创建成功的ID
r($result)        && p('1') && e('7');  // 判断批量创建8条待办，返回创建成功的ID
r($result)        && p('2') && e('8');  // 判断批量创建8条待办，返回创建成功的ID
r($result)        && p('3') && e('9');  // 判断批量创建8条待办，返回创建成功的ID