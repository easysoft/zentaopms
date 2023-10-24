#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->close();
cid=1
pid=1

关闭一个用户需求，查看变更的字段1 >> assigedTo,,closed
关闭一个用户需求，查看变更的字段2 >> status,active,closed
关闭一个用户需求，查看变更的字段3 >> stage,wait,closed
关闭一个软件需求，查看变更的字段1 >> closedReason,subdivided,willnotdo
关闭一个软件需求，查看变更的字段2 >> assignedTo,,closed
关闭一个软件需求，查看变更的字段3 >> status,active,closed

*/

$story = new storyTest();
$changes1 = $story->closeTest(1, array('closedReason' => 'done'));
$changes2 = $story->closeTest(2, array('closedReason' => 'willnotdo'));

r($changes1) && p('0:field,old,new') && e('assigedTo,,closed');    // 关闭一个用户需求，查看变更的字段1
r($changes1) && p('1:field,old,new') && e('status,active,closed'); // 关闭一个用户需求，查看变更的字段2
r($changes1) && p('2:field,old,new') && e('stage,wait,closed');    // 关闭一个用户需求，查看变更的字段3
r($changes2) && p('0:field,old,new') && e('closedReason,subdivided,willnotdo'); // 关闭一个软件需求，查看变更的字段1
r($changes2) && p('1:field,old,new') && e('assignedTo,,closed');                // 关闭一个软件需求，查看变更的字段2
r($changes2) && p('2:field,old,new') && e('status,active,closed');              // 关闭一个软件需求，查看变更的字段3
