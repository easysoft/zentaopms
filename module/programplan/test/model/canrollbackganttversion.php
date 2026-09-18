#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->canRollbackGanttVersion();
timeout=0
cid=0

- 未关闭项目允许回滚 @1
- 已关闭项目禁止回滚 @0
- 配置允许修改已关闭项目时允许回滚 @1
- 存在已关闭执行时禁止回滚 @0
- 配置允许修改已关闭执行时允许回滚 @1
- 已删除的已关闭执行不影响回滚 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$project = zenData('project');
$project->id->range('1-6');
$project->project->range('101,101,102,103,103,104');
$project->status->range('closed,doing,closed,closed,doing,wait');
$project->deleted->range('0,0,1,0,0,0');
$project->gen(6);

su('admin');

global $config;
$config->CRProject   = 0;
$config->CRExecution = 0;

$programplan = new programplanModelTest();

$openProject          = (object)array('id' => 104, 'status' => 'doing');
$closedProject        = (object)array('id' => 105, 'status' => 'closed');
$projectWithClosed    = (object)array('id' => 103, 'status' => 'doing');
$projectWithDeleted   = (object)array('id' => 102, 'status' => 'doing');

r((int)$programplan->canRollbackGanttVersionTest($openProject))        && p() && e('1'); // 未关闭项目允许回滚
r((int)$programplan->canRollbackGanttVersionTest($closedProject))      && p() && e('0'); // 已关闭项目禁止回滚

$config->CRProject = 1;
r((int)$programplan->canRollbackGanttVersionTest($closedProject))      && p() && e('1'); // 配置允许修改已关闭项目时允许回滚

$config->CRProject = 0;
r((int)$programplan->canRollbackGanttVersionTest($projectWithClosed))  && p() && e('0'); // 存在已关闭执行时禁止回滚

$config->CRExecution = 1;
r((int)$programplan->canRollbackGanttVersionTest($projectWithClosed))  && p() && e('1'); // 配置允许修改已关闭执行时允许回滚

$config->CRExecution = 0;
r((int)$programplan->canRollbackGanttVersionTest($projectWithDeleted)) && p() && e('1'); // 已删除的已关闭执行不影响回滚

$config->CRProject   = 0;
$config->CRExecution = 0;
