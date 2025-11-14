#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processExecutionRecord();
timeout=0
cid=18335

- 测试普通执行类型(sprint),应生成view链接 >> 验证URL包含execution-view且extraType为空
- 测试看板执行类型(kanban),应生成kanban链接 >> 验证URL包含execution-kanban且extraType为kanban
- 测试迭代执行类型(stage),应生成view链接 >> 验证URL包含execution-view且extraType为stage
- 测试空类型执行,应生成view链接 >> 验证URL包含execution-view且extraType为空
- 测试另一个普通执行,验证ID和URL正确生成 >> 验证URL包含execution-view且record ID正确

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->type->range('sprint,kanban,stage,sprint,sprint,kanban,sprint,stage,sprint,sprint');
$project->status->range('doing');
$project->deleted->range('0');
$project->gen(10);

su('admin');

$searchTest = new searchTaoTest();

$record1 = (object)array('id' => 1, 'objectID' => 1, 'objectType' => 'execution', 'title' => 'Execution 1', 'url' => '', 'extraType' => '');
$objectList1 = array('execution' => array(1 => (object)array('id' => 1, 'type' => 'sprint', 'project' => 1)));
$result1 = $searchTest->processExecutionRecordTest($record1, $objectList1);
r(strpos($result1->url, 'execution-view') !== false && $result1->extraType == '') && p() && e('1');

$record2 = (object)array('id' => 2, 'objectID' => 2, 'objectType' => 'execution', 'title' => 'Execution 2', 'url' => '', 'extraType' => '');
$objectList2 = array('execution' => array(2 => (object)array('id' => 2, 'type' => 'kanban', 'project' => 2)));
$result2 = $searchTest->processExecutionRecordTest($record2, $objectList2);
r(strpos($result2->url, 'execution-kanban') !== false && $result2->extraType == 'kanban') && p() && e('1');

$record3 = (object)array('id' => 3, 'objectID' => 3, 'objectType' => 'execution', 'title' => 'Execution 3', 'url' => '', 'extraType' => '');
$objectList3 = array('execution' => array(3 => (object)array('id' => 3, 'type' => 'stage', 'project' => 3)));
$result3 = $searchTest->processExecutionRecordTest($record3, $objectList3);
r(strpos($result3->url, 'execution-view') !== false && $result3->extraType == 'stage') && p() && e('1');

$record4 = (object)array('id' => 4, 'objectID' => 4, 'objectType' => 'execution', 'title' => 'Execution 4', 'url' => '', 'extraType' => '');
$objectList4 = array('execution' => array(4 => (object)array('id' => 4, 'type' => '', 'project' => 4)));
$result4 = $searchTest->processExecutionRecordTest($record4, $objectList4);
r(strpos($result4->url, 'execution-view') !== false && $result4->extraType == '') && p() && e('1');

$record5 = (object)array('id' => 5, 'objectID' => 5, 'objectType' => 'execution', 'title' => 'Execution 5', 'url' => '', 'extraType' => '');
$objectList5 = array('execution' => array(5 => (object)array('id' => 5, 'type' => 'sprint', 'project' => 5)));
$result5 = $searchTest->processExecutionRecordTest($record5, $objectList5);
r(strpos($result5->url, 'execution-view') !== false && $result5->id == 5) && p() && e('1');
