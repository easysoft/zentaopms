#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/todo.class.php';
su('admin');

/**

title=测试 todoModel->assignTo();
cid=1
pid=1

指派todo 1给test1 >> test1,20300101,1000,1400
指派todo 2给test1 >> test1,2400,2400
指派todo 3给test1 >> test1,1002,1402

*/

$todoIDList = array('1', '2', '3');

$todo1 = new stdclass();
$todo1->assignedTo     = 'test1';
$todo1->future         = 'on';
$todo1->begin          = 1000;
$todo1->end            = 1400;

$todo2 = new stdclass();
$todo2->assignedTo     = 'test1';
$todo2->lblDisableDate = 'on';

$todo3 = new stdclass();
$todo3->assignedTo     = 'test1';
$todo3->begin          = 1002;
$todo3->end            = 1402;

$todo = new todoTest();

r($todo->assignToTest($todoIDList[0], $todo1)) && p('assignedTo,date,begin,end') && e('test1,20300101,1000,1400'); // 指派todo 1给test1
r($todo->assignToTest($todoIDList[1], $todo2)) && p('assignedTo,begin,end')      && e('test1,2400,2400');          // 指派todo 2给test1
r($todo->assignToTest($todoIDList[2], $todo3)) && p('assignedTo,begin,end')      && e('test1,1002,1402');          // 指派todo 3给test1
