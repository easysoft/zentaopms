#!/usr/bin/env php
<?php
/**
title=测试 userTao->fetchProjectExecutionCount();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$projectTable = zdTable('project');
$projectTable->project->range('1-4{6}');
$projectTable->type->range('sprint,stage,kanban');
$projectTable->multiple->range('1{4},0,1');
$projectTable->vision->range('rnd{12},lite{8}');
$projectTable->deleted->range('0{5},1');
$projectTable->gen(20);

global $config;

$userTest = new userTest();

$projectIdList = array(1, 2, 3, 4);

$config->vision = 'rnd';
$executions = $userTest->fetchProjectExecutionCountTest($projectIdList);
r(count($executions)) && p()  && e(2);
r($executions)        && p(1) && e(4);
r($executions)        && p(2) && e(4);

$config->vision = 'lite';
$executions = $userTest->fetchProjectExecutionCountTest($projectIdList);
r(count($executions)) && p()  && e(2);
r($executions)        && p(3) && e(4);
r($executions)        && p(4) && e(2);
