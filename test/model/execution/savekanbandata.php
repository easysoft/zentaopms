#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试executionModel->saveKanbanData();
cid=1
pid=1

保存执行162看板数据   >> 246
保存执行162看板空数据 >> empty

*/

$execution = new executionTest();

$executionID = 162;
$emptyData   = array();

r($execution->saveKanbanDataTest($executionID))             && p('story:0') && e('246');       //保存执行162看板数据
r($execution->saveKanbanDataTest($executionID, $emptyData)) && p('')        && e('empty');       //保存执行162看板空数据
