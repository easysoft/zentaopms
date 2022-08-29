#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getKanbanGroupData();
cid=1
pid=1

空数据查询      >> empty
查询执行162需求 >> 软件需求246

*/

$execution = new executionTest();

$executionID = 162;
$stories     = $tester->loadModel('story')->getExecutionStories($executionID);
$tasks       = $execution->getKanbanTasksTest($executionID, false);
$bugs        = $tester->loadModel('bug')->getExecutionBugs($executionID);

r($execution->getKanbanGroupDataTest(array(), array(), array(), 'story')) && p('')            && e('empty');       //空数据查询
r($execution->getKanbanGroupDataTest($stories, $tasks, $bugs, 'story'))   && p('246:title') && e('软件需求246'); // 查询执行162需求
