#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
$db->switchDB();

/**

title=bugModel->activate();
cid=1
pid=1

测试激活状态为active的bug1 >> activatedCount,0,1
测试激活状态为active的bug2 >> activatedCount,0,1
测试激活状态为resolved的bug51 >> assignedTo,dev1,admin;activatedCount,0,1
测试激活状态为resolved的bug52 >> assignedTo,dev1,admin;activatedCount,0,1
测试激活状态为closed的bug81 >> assignedTo,test1,admin;activatedCount,0,1
测试激活状态为closed的bug82 >> assignedTo,test1,admin;activatedCount,0,1

*/

$bugIDList = array('1', '2', '51', '52', '81', '82');

$bug=new bugTest();
r($bug->activateObject($bugIDList[0])) && p('2:field,old,new')                 && e('activatedCount,0,1');                        // 测试激活状态为active的bug1
r($bug->activateObject($bugIDList[1])) && p('1:field,old,new')                 && e('activatedCount,0,1');                        // 测试激活状态为active的bug2
r($bug->activateObject($bugIDList[2])) && p('0:field,old,new;2:field,old,new') && e('assignedTo,dev1,admin;activatedCount,0,1');  // 测试激活状态为resolved的bug51
r($bug->activateObject($bugIDList[3])) && p('0:field,old,new;2:field,old,new') && e('assignedTo,dev1,admin;activatedCount,0,1');  // 测试激活状态为resolved的bug52
r($bug->activateObject($bugIDList[4])) && p('0:field,old,new;2:field,old,new') && e('assignedTo,test1,admin;activatedCount,0,1'); // 测试激活状态为closed的bug81
r($bug->activateObject($bugIDList[5])) && p('0:field,old,new;2:field,old,new') && e('assignedTo,test1,admin;activatedCount,0,1'); // 测试激活状态为closed的bug82
$db->restoreDB();