#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->getPrevKanban();
cid=1
pid=1

查询执行162数据 >> empty
保存数据后查询执行162数据 >> 246

*/

$execution = new executionTest();

$emptyExecutionID = 163;
$executionID      = 162;

r($execution->getPrevKanbanTest($emptyExecutionID)) && p('')        && e('empty'); //查询执行162数据
$execution->saveKanbanDataTest($executionID);
r($execution->getPrevKanbanTest($executionID))      && p('story:0') && e('246');   //保存数据后查询执行162数据
