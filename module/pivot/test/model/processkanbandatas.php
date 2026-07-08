#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processKanbanDatas();
timeout=0
cid=0

- 步骤1：需求关联看板项目时标记弹窗 @1
- 步骤2：需求关联普通项目时不标记弹窗 @0
- 步骤3：任务执行属于看板项目时标记弹窗 @1
- 步骤4：Bug 执行属于普通项目时不标记弹窗 @0
- 步骤5：空数据直接返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $tester;
$dao = $tester->dao;

$dao->delete()->from(TABLE_PROJECT)->where('id')->in('101,102')->exec();
$dao->delete()->from(TABLE_PROJECTSTORY)->where('story')->in('201,202')->exec();

$dao->insert(TABLE_PROJECT)->data(array('id' => 101, 'name' => '看板项目', 'type' => 'kanban', 'deleted' => '0'))->exec();
$dao->insert(TABLE_PROJECT)->data(array('id' => 102, 'name' => '瀑布项目', 'type' => 'project', 'deleted' => '0'))->exec();

$dao->insert(TABLE_PROJECTSTORY)->data(array('project' => 101, 'story' => 201))->exec();
$dao->insert(TABLE_PROJECTSTORY)->data(array('project' => 102, 'story' => 202))->exec();

su('admin');

$pivotTest = new pivotModelTest();

$storyResult = $pivotTest->processKanbanDatasTest('story', array((object)array('id' => 201), (object)array('id' => 202)));
$taskResult  = $pivotTest->processKanbanDatasTest('task',  array((object)array('id' => 301, 'execution' => 101)));
$bugResult   = $pivotTest->processKanbanDatasTest('bug',   array((object)array('id' => 401, 'execution' => 102)));

r($storyResult[0]->isModal) && p() && e('1');
r(isset($storyResult[1]->isModal)) && p() && e('0');
r($taskResult[0]->isModal) && p() && e('1');
r(isset($bugResult[0]->isModal)) && p() && e('0');
r(count($pivotTest->processKanbanDatasTest('story', array()))) && p() && e('0');
