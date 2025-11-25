#!/usr/bin/env php
<?php

/**

title=测试 bugZen::updateBug();
timeout=0
cid=15481

- 执行bugTest模块的updateBugTest方法，参数是$bug1, array 属性status @resolved
- 执行bugTest模块的updateBugTest方法，参数是$bug2, array
 - 属性title @Updated Title
 - 属性assignedTo @admin
- 执行bugTest模块的updateBugTest方法，参数是$bug3, array
 - 属性title @Third Bug
 - 属性status @active
- 执行bugTest模块的updateBugTest方法，参数是$bug4, array
 - 属性title @New Title
 - 属性status @closed
 - 属性assignedTo @user5
 - 属性severity @1
 - 属性pri @1
- 执行bugTest模块的updateBugTest方法，参数是$bug5, array 属性newField @newValue

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$bugTest = new bugZenTest();

$bug1 = new stdClass();
$bug1->id = 1;
$bug1->title = 'Original Bug Title';
$bug1->status = 'active';
$bug1->assignedTo = 'user1';

$bug2 = new stdClass();
$bug2->id = 2;
$bug2->title = 'Another Bug';
$bug2->status = 'resolved';
$bug2->assignedTo = 'user2';

$bug3 = new stdClass();
$bug3->id = 3;
$bug3->title = 'Third Bug';
$bug3->status = 'active';

$bug4 = new stdClass();
$bug4->id = 4;
$bug4->title = 'Fourth Bug';
$bug4->status = 'active';
$bug4->assignedTo = 'user3';
$bug4->severity = 3;
$bug4->pri = 2;

$bug5 = new stdClass();
$bug5->id = 5;
$bug5->title = 'Fifth Bug';

r($bugTest->updateBugTest($bug1, array('status' => 'resolved'))) && p('status') && e('resolved');
r($bugTest->updateBugTest($bug2, array('title' => 'Updated Title', 'assignedTo' => 'admin'))) && p('title,assignedTo') && e('Updated Title,admin');
r($bugTest->updateBugTest($bug3, array())) && p('title,status') && e('Third Bug,active');
r($bugTest->updateBugTest($bug4, array('title' => 'New Title', 'status' => 'closed', 'assignedTo' => 'user5', 'severity' => 1, 'pri' => 1))) && p('title,status,assignedTo,severity,pri') && e('New Title,closed,user5,1,1');
r($bugTest->updateBugTest($bug5, array('newField' => 'newValue'))) && p('newField') && e('newValue');