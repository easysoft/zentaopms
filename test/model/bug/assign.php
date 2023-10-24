#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->assign();
cid=1
pid=1

指派bugID为1的bug >> assignedTo,admin,user92
指派bugID为2的bug >> assignedTo,admin,user93
指派bugID为3的bug >> assignedTo,admin,user94
指派bugID为4的bug >> assignedTo,admin,user95
指派bugID为51的bug >> assignedTo,dev1,user96
指派人不发生变化的bug >> 0

*/

$bugIDlist = array('1','2','3','4','51','81');

$bug1  = array('assignedTo' => 'user92', 'status' => 'active');
$bug2  = array('assignedTo' => 'user93', 'status' => 'active');
$bug3  = array('assignedTo' => 'user94', 'status' => 'active');
$bug4  = array('assignedTo' => 'user95', 'status' => 'active');
$bug51 = array('assignedTo' => 'user96', 'status' => 'active');
$bug81 = array('assignedTo' => 'user97', 'status' => 'active');

$bug = new bugTest();
r($bug->assignTest($bugIDlist[0],$bug1))  && p('0:field,old,new') && e('assignedTo,admin,user92'); // 指派bugID为1的bug
r($bug->assignTest($bugIDlist[1],$bug2))  && p('0:field,old,new') && e('assignedTo,admin,user93'); // 指派bugID为2的bug
r($bug->assignTest($bugIDlist[2],$bug3))  && p('0:field,old,new') && e('assignedTo,admin,user94'); // 指派bugID为3的bug
r($bug->assignTest($bugIDlist[3],$bug4))  && p('0:field,old,new') && e('assignedTo,admin,user95'); // 指派bugID为4的bug
r($bug->assignTest($bugIDlist[4],$bug51)) && p('0:field,old,new') && e('assignedTo,dev1,user96');  // 指派bugID为51的bug
r($bug->assignTest($bugIDlist[5],$bug81)) && p() && e('0');                                        // 指派人不发生变化的bug
