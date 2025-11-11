#!/usr/bin/env php
<?php

/**

title=测试 storyZen::setViewVarsForKanban();
timeout=0
cid=0

- 执行storyTest模块的setViewVarsForKanbanTest方法，参数是3, array 属性executionType @kanban
- 执行storyTest模块的setViewVarsForKanbanTest方法，参数是3, array 属性executionType @kanban
- 执行storyTest模块的setViewVarsForKanbanTest方法，参数是0, array 属性executionType @~~
- 执行storyTest模块的setViewVarsForKanbanTest方法，参数是4, array 属性executionType @~~
- 执行storyTest模块的setViewVarsForKanbanTest方法，参数是3, array 属性executionType @kanban

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

su('admin');

global $tester;

// 直接插入测试数据到数据库
$tester->dao->delete()->from(TABLE_EXECUTION)->where('id')->in('1,2,3,4,5,6,7,8,9,10')->exec();

// 插入看板类型执行
$tester->dao->insert(TABLE_EXECUTION)->data(array(
    'id' => 1,
    'project' => 1,
    'type' => 'kanban',
    'name' => '看板执行1',
    'status' => 'doing',
    'deleted' => '0'
))->exec();

$tester->dao->insert(TABLE_EXECUTION)->data(array(
    'id' => 2,
    'project' => 1,
    'type' => 'kanban',
    'name' => '看板执行2',
    'status' => 'doing',
    'deleted' => '0'
))->exec();

$tester->dao->insert(TABLE_EXECUTION)->data(array(
    'id' => 3,
    'project' => 1,
    'type' => 'kanban',
    'name' => '看板执行3',
    'status' => 'doing',
    'deleted' => '0'
))->exec();

// 插入非看板类型执行
$tester->dao->insert(TABLE_EXECUTION)->data(array(
    'id' => 4,
    'project' => 1,
    'type' => 'sprint',
    'name' => '冲刺1',
    'status' => 'doing',
    'deleted' => '0'
))->exec();

$tester->dao->insert(TABLE_EXECUTION)->data(array(
    'id' => 5,
    'project' => 1,
    'type' => 'stage',
    'name' => '阶段1',
    'status' => 'doing',
    'deleted' => '0'
))->exec();

$storyTest = new storyZenTest();

r($storyTest->setViewVarsForKanbanTest(3, array('regionID' => 1, 'laneID' => 1), 'story')) && p('executionType') && e('kanban');
r($storyTest->setViewVarsForKanbanTest(3, array(), 'story')) && p('executionType') && e('kanban');
r($storyTest->setViewVarsForKanbanTest(0, array('regionID' => 1, 'laneID' => 1), 'story')) && p('executionType') && e('~~');
r($storyTest->setViewVarsForKanbanTest(4, array('regionID' => 1, 'laneID' => 1), 'story')) && p('executionType') && e('~~');
r($storyTest->setViewVarsForKanbanTest(3, array('regionID' => 1, 'laneID' => 1), 'requirement')) && p('executionType') && e('kanban');