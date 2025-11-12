#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processProjectRecord();
timeout=0
cid=0

- 执行searchTest模块的processProjectRecordTest方法,项目模型为scrum,参数是$record1, $objectList1 >> url属性包含project-view-1
- 执行searchTest模块的processProjectRecordTest方法,项目模型为waterfall,参数是$record2, $objectList2 >> url属性包含project-view-2
- 执行searchTest模块的processProjectRecordTest方法,项目模型为kanban,参数是$record3, $objectList3 >> url属性包含project-index-3
- 执行searchTest模块的processProjectRecordTest方法,项目模型为空,参数是$record4, $objectList4 >> url属性包含project-view-4
- 执行searchTest模块的processProjectRecordTest方法,项目模型为agileplus,参数是$record5, $objectList5 >> url属性包含project-view-5
- 执行searchTest模块的processProjectRecordTest方法,项目模型为waterfallplus,参数是$record6, $objectList6 >> url属性包含project-view-6
- 执行searchTest模块的processProjectRecordTest方法,项目ID为100,参数是$record7, $objectList7 >> url属性包含project-index-100

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('project')->gen(10);

su('admin');

$searchTest = new searchTaoTest();

$record1 = new stdClass();
$record1->objectType = 'project';
$record1->objectID = 1;

$record2 = new stdClass();
$record2->objectType = 'project';
$record2->objectID = 2;

$record3 = new stdClass();
$record3->objectType = 'project';
$record3->objectID = 3;

$record4 = new stdClass();
$record4->objectType = 'project';
$record4->objectID = 4;

$record5 = new stdClass();
$record5->objectType = 'project';
$record5->objectID = 5;

$record6 = new stdClass();
$record6->objectType = 'project';
$record6->objectID = 6;

$record7 = new stdClass();
$record7->objectType = 'project';
$record7->objectID = 100;

$project1 = new stdClass();
$project1->model = 'scrum';

$project2 = new stdClass();
$project2->model = 'waterfall';

$project3 = new stdClass();
$project3->model = 'kanban';

$project4 = new stdClass();
$project4->model = '';

$project5 = new stdClass();
$project5->model = 'agileplus';

$project6 = new stdClass();
$project6->model = 'waterfallplus';

$project7 = new stdClass();
$project7->model = 'kanban';

$objectList1 = array('project' => array(1 => $project1));
$objectList2 = array('project' => array(2 => $project2));
$objectList3 = array('project' => array(3 => $project3));
$objectList4 = array('project' => array(4 => $project4));
$objectList5 = array('project' => array(5 => $project5));
$objectList6 = array('project' => array(6 => $project6));
$objectList7 = array('project' => array(100 => $project7));

r($searchTest->processProjectRecordTest($record1, $objectList1)) && p('url') && e('*/project-view-1.html');
r($searchTest->processProjectRecordTest($record2, $objectList2)) && p('url') && e('*/project-view-2.html');
r($searchTest->processProjectRecordTest($record3, $objectList3)) && p('url') && e('*/project-index-3.html');
r($searchTest->processProjectRecordTest($record4, $objectList4)) && p('url') && e('*/project-view-4.html');
r($searchTest->processProjectRecordTest($record5, $objectList5)) && p('url') && e('*/project-view-5.html');
r($searchTest->processProjectRecordTest($record6, $objectList6)) && p('url') && e('*/project-view-6.html');
r($searchTest->processProjectRecordTest($record7, $objectList7)) && p('url') && e('*/project-index-100.html');
