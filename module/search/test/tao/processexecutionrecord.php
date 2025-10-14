#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processExecutionRecord();
timeout=0
cid=0

- 执行searchTest模块的processExecutionRecordTest方法 属性extraType @sprint
- 执行searchTest模块的processExecutionRecordTest方法 属性extraType @kanban
- 执行searchTest模块的processExecutionRecordTest方法 属性extraType @~~
- 执行searchTest模块的processExecutionRecordTest方法 属性extraType @stage
- 执行searchTest模块的processExecutionRecordTest方法 属性extraType @sprint

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

$table = zenData('project');
$table->id->range('1-10');
$table->name->range('执行1,执行2,执行3,执行4,执行5,执行6,执行7,执行8,执行9,执行10');
$table->type->range('sprint{4},kanban{3},stage{3}');
$table->status->range('wait{3},doing{4},done{3}');
$table->deleted->range('0');
$table->gen(10);

su('admin');

$searchTest = new searchTest();

r($searchTest->processExecutionRecordTest((object)array('objectType' => 'execution', 'objectID' => 1, 'title' => 'Test Execution'), array('execution' => array(1 => (object)array('type' => 'sprint', 'project' => 1))))) && p('extraType') && e('sprint');
r($searchTest->processExecutionRecordTest((object)array('objectType' => 'execution', 'objectID' => 2, 'title' => 'Kanban Execution'), array('execution' => array(2 => (object)array('type' => 'kanban', 'project' => 2))))) && p('extraType') && e('kanban');
r($searchTest->processExecutionRecordTest((object)array('objectType' => 'execution', 'objectID' => 3, 'title' => 'Stage Execution'), array('execution' => array(3 => (object)array('type' => '', 'project' => 3))))) && p('extraType') && e('~~');
r($searchTest->processExecutionRecordTest((object)array('objectType' => 'execution', 'objectID' => 4, 'title' => 'Normal Execution'), array('execution' => array(4 => (object)array('type' => 'stage', 'project' => 4))))) && p('extraType') && e('stage');
r($searchTest->processExecutionRecordTest((object)array('objectType' => 'execution', 'objectID' => 5, 'title' => 'Another Execution'), array('execution' => array(5 => (object)array('type' => 'sprint', 'project' => 5))))) && p('extraType') && e('sprint');