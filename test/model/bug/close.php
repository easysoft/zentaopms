#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->close();
cid=1
pid=1

测试关闭状态为active的bug1 >> assignedTo,admin,closed;status,active,closed
测试关闭状态为active的bug2 >> assignedTo,admin,closed;status,active,closed
测试关闭状态为resolved的bug51 >> assignedTo,dev1,closed;status,resolved,closed
测试关闭状态为resolved的bug52 >> assignedTo,dev1,closed;status,resolved,closed
测试关闭状态为closed的bug81 >> assignedTo,dev1,closed;status,active,closed
测试关闭状态为closed的bug82 >> assignedTo,dev1,closed;status,active,closed

*/

$bugIDList = array('1', '2', '51', '52', '34', '36');

$bug=new bugTest();
r($bug->closeObject($bugIDList[0])) && p('0:field,old,new;1:field,old,new') && e('assignedTo,admin,closed;status,active,closed');  // 测试关闭状态为active的bug1
r($bug->closeObject($bugIDList[1])) && p('0:field,old,new;1:field,old,new') && e('assignedTo,admin,closed;status,active,closed');  // 测试关闭状态为active的bug2
r($bug->closeObject($bugIDList[2])) && p('0:field,old,new;1:field,old,new') && e('assignedTo,dev1,closed;status,resolved,closed'); // 测试关闭状态为resolved的bug51
r($bug->closeObject($bugIDList[3])) && p('0:field,old,new;1:field,old,new') && e('assignedTo,dev1,closed;status,resolved,closed'); // 测试关闭状态为resolved的bug52
r($bug->closeObject($bugIDList[4])) && p('0:field,old,new;1:field,old,new') && e('assignedTo,dev1,closed;status,active,closed');   // 测试关闭状态为closed的bug81
r($bug->closeObject($bugIDList[5])) && p('0:field,old,new;1:field,old,new') && e('assignedTo,dev1,closed;status,active,closed');   // 测试关闭状态为closed的bug82
