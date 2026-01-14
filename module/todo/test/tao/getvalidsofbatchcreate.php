#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

zenData('todo')->gen(0);
/**

title=测试 todoTao->getValidsOfBatchCreate();
timeout=0
cid=19815

- 获取批量创建有效的数据，结果为1
 - 属性account @admin
 - 属性type @custom
 - 属性name @批量创建待办2
 - 属性desc @desc2
 - 属性status @wait

*/

$today       = date('Y-m-d');
$assignedTo  = 'admin';
$names       = array('批量创建待办1', '批量创建待办2', '批量创建待办3', '批量创建待办4', '批量创建待办5', '批量创建待办6', '批量创建待办7', '批量创建待办8');
$types       = array('custom', 'custom', 'custom', 'custom', 'custom', 'custom', 'custom', 'custom');
$pris        = array(1, 2, 3, 4, 1, 2, 3, 4);
$descs       = array('desc1', 'desc2', 'desc3', 'desc4', '' , '', '', '');
$begins      = array('0830', '0900', '0930', '1000', '1030', '1100', '1130', '1200');
$ends        = array('0900', '0930', '1000','1030', '1100', '1130', '1200', '1230');
$assignedTos = array('admin', 'productManager', 'projectManager', 'dev1', 'dev2', 'dev3', 'tester1', 'tester2',);
$todos       = array('type' => $types, 'pri' => $pris, 'name' => $names, 'desc' => $descs, 'begin' => $begins, 'end' => $ends, 'assignedTo' => $assignedTos, 'date' => $today, 'switchDate' => '');

$todoTest   = new todoTaoTest();
r($todoTest->getValidsOfBatchCreateTest($todos, 1, $assignedTo)) && p('account,type,name,desc,status') && e('admin,custom,批量创建待办2,desc2,wait'); // 获取批量创建有效的数据，结果为1
