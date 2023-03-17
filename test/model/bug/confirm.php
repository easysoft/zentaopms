#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->confirm();
cid=1
pid=1

确认指派人变化的bug >> assignedTo,admin,user92;confirmed,0,1
确认类型变化的bug >> type,install,codeerror;confirmed,0,1
确认已确认的bug >> assignedTo,admin,user95
确认优先级变化的bug >> status,resolved,active;pri,3,2
确认bug >> status,closed,active

*/

$bugIDlist = array('1','3','4','51','81');

$bug1 = array('assignedTo' => 'user92', 'status' => 'active', 'type' => 'codeerror', 'pri' => '1');
$bug3 = array('assignedTo' => 'admin' , 'status' => 'active', 'type' => 'codeerror', 'pri' => '3');
$bug4 = array('assignedTo' => 'user95' , 'status' => 'active', 'type' => 'security',  'pri' => '4');
$bug5 = array('assignedTo' => 'dev1'  , 'status' => 'active', 'type' => 'standard',  'pri' => '2');
$bug8 = array('assignedTo' => 'test1' , 'status' => 'active', 'type' => 'others',    'pri' => '1');

$bug = new bugTest();
r($bug->confirmTest($bugIDlist[0],$bug1)) && p('0:field,old,new;1:field,old,new') && e('assignedTo,admin,user92;confirmed,0,1'); // 确认指派人变化的bug
r($bug->confirmTest($bugIDlist[1],$bug3)) && p('0:field,old,new;1:field,old,new') && e('type,install,codeerror;confirmed,0,1');  // 确认类型变化的bug
r($bug->confirmTest($bugIDlist[2],$bug4)) && p('0:field,old,new')                 && e('assignedTo,admin,user95');               // 确认已确认的bug
r($bug->confirmTest($bugIDlist[3],$bug5)) && p('0:field,old,new;1:field,old,new') && e('status,resolved,active;pri,3,2');        // 确认优先级变化的bug
r($bug->confirmTest($bugIDlist[4],$bug8)) && p('0:field,old,new')                 && e('status,closed,active');                  // 确认bug
