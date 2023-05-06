#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

$project = zdTable('project')->config('1')->gen(8);

/**

title=测试 projectTao::getstats4kanban
timeout=0
cid=1

- 执行$kanbanGroup['kanbanGroup'] @2

- 执行$kanbanGroup['kanbanGroup']['other'] @3

- 执行$kanbanGroup['kanbanGroup']['my'] @3

- 执行$kanbanGroup['latestExecutions'] @1

- 执行kanbanGroup['kanbanGroup']['other'][2]['wait'][0]模块的status方法 @wait

- 执行kanbanGroup['kanbanGroup']['other'][4]['doing'][0]模块的status方法 @doing

- 执行kanbanGroup['kanbanGroup']['my'][1]['wait'][0]模块的status方法 @wait

- 执行kanbanGroup['kanbanGroup']['my'][3]['doing'][0]模块的status方法 @doing

- 执行kanbanGroup['kanbanGroup']['my'][5]['doing'][0]模块的status方法 @doing

- 执行kanbanGroup['latestExecutions'][6]模块的status方法 @doing

*/

global $tester;
$tester->loadModel('project');
$kanbanGroup = $tester->project->getStats4Kanban();

r(count($kanbanGroup['kanbanGroup']))                          && p() && e('2');
r(count($kanbanGroup['kanbanGroup']['other']))                 && p() && e('3');
r(count($kanbanGroup['kanbanGroup']['my']))                    && p() && e('3');
r(count($kanbanGroup['latestExecutions']))                     && p() && e('1');
r($kanbanGroup['kanbanGroup']['other'][2]['wait'][0]->status)  && p() && e('wait');
r($kanbanGroup['kanbanGroup']['other'][4]['doing'][0]->status) && p() && e('doing');
r($kanbanGroup['kanbanGroup']['my'][1]['wait'][0]->status)     && p() && e('wait');
r($kanbanGroup['kanbanGroup']['my'][3]['doing'][0]->status)    && p() && e('doing');
r($kanbanGroup['kanbanGroup']['my'][5]['doing'][0]->status)    && p() && e('doing');
r($kanbanGroup['latestExecutions'][6]->status)                 && p() && e('doing');
