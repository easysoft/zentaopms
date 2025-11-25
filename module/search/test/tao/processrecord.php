#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processRecord();
timeout=0
cid=18338

- 测试issue类型的记录处理,lib为空 >> url属性包含issue-view-1
- 测试project类型的记录处理,项目模型为scrum >> url属性包含project-view-2
- 测试execution类型的记录处理,执行类型为stage >> url属性包含execution-view-3
- 测试project类型的记录处理,项目模型为kanban >> url属性包含project-index-4
- 测试bug类型的记录处理 >> url属性包含bug-view-5
- 测试story类型的记录处理,lib为空 >> url属性包含story-storyView-6
- 测试普通类型的记录处理(testcase) >> url属性包含testcase-view-7

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('project')->gen(10);
zenData('task')->gen(10);

su('admin');

$searchTest = new searchTaoTest();

$record1 = new stdClass();
$record1->objectType = 'issue';
$record1->objectID = 1;

$record2 = new stdClass();
$record2->objectType = 'project';
$record2->objectID = 2;

$record3 = new stdClass();
$record3->objectType = 'execution';
$record3->objectID = 3;

$record4 = new stdClass();
$record4->objectType = 'project';
$record4->objectID = 4;

$record5 = new stdClass();
$record5->objectType = 'bug';
$record5->objectID = 5;

$record6 = new stdClass();
$record6->objectType = 'story';
$record6->objectID = 6;

$record7 = new stdClass();
$record7->objectType = 'testcase';
$record7->objectID = 7;

$issue1 = new stdClass();
$issue1->lib = '';
$issue1->owner = '';
$issue1->project = 1;

$project2 = new stdClass();
$project2->id = 2;
$project2->model = 'scrum';

$execution3 = new stdClass();
$execution3->id = 3;
$execution3->type = 'stage';
$execution3->project = 1;

$project4 = new stdClass();
$project4->id = 4;
$project4->model = 'kanban';

$story6 = new stdClass();
$story6->id = 6;
$story6->type = 'story';
$story6->lib = '';

$objectList1 = array('issue' => array(1 => $issue1));
$objectList2 = array('project' => array(2 => $project2));
$objectList3 = array('execution' => array(3 => $execution3));
$objectList4 = array('project' => array(4 => $project4));
$objectList5 = array();
$objectList6 = array('story' => array(6 => $story6));
$objectList7 = array();

r($searchTest->processRecordTest($record1, $objectList1)) && p('url') && e('*/issue-view-1.html');
r($searchTest->processRecordTest($record2, $objectList2)) && p('url') && e('*/project-view-2.html');
r($searchTest->processRecordTest($record3, $objectList3)) && p('url') && e('*/execution-view-3.html');
r($searchTest->processRecordTest($record4, $objectList4)) && p('url') && e('*/project-index-4.html');
r($searchTest->processRecordTest($record5, $objectList5)) && p('url') && e('*/bug-view-5.html');
r($searchTest->processRecordTest($record6, $objectList6)) && p('url') && e('*/story-storyView-6.html');
r($searchTest->processRecordTest($record7, $objectList7)) && p('url') && e('*/testcase-view-7.html');
