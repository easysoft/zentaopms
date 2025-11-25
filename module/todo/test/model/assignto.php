#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

function initData()
{
    zenData('todo')->loadYaml('assignto')->gen(5);
}

/**

title=测试 todoModel->assignTo();
timeout=0
cid=19246

*/

initData();

$todoIDList = range(1,5);

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

$todo4 = new stdclass();
$todo4->assignedTo     = 'admin';
$todo4->future         = 'on';
$todo4->begin          = 1200;
$todo4->end            = 1502;

$todo5 = new stdclass();
$todo5->assignedTo     = 'admin';
$todo5->future         = 'on';
$todo5->lblDisableDate = 'on';

$todo = new todoTest();

r($todo->assignToTest($todoIDList[0], $todo1)) && p('assignedTo,date,begin,end') && e('test1,20300101,1000,1400'); // 指派todo 1给test1 选择待定
r($todo->assignToTest($todoIDList[1], $todo2)) && p('assignedTo,begin,end')      && e('test1,2400,2400');          // 指派todo 2给test1 暂时不设定时间
r($todo->assignToTest($todoIDList[2], $todo3)) && p('assignedTo,begin,end')      && e('test1,1002,1402');          // 指派todo 3给test1
r($todo->assignToTest($todoIDList[3], $todo4)) && p('assignedTo,date,begin,end') && e('admin,20300101,1200,1502'); // 指派todo 4给admin future=on 验证 date默认值
r($todo->assignToTest($todoIDList[4], $todo5)) && p('assignedTo,date,begin,end') && e('admin,20300101,2400,2400'); // 指派todo 5给admin future=on lblDisableDate=on 验证date,begin,end默认值
