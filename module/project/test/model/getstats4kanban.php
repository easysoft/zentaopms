#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

$project = zdTable('project')->config('project')->gen(8);

/**

title=测试 projectTao::getstats4kanban
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('project');
list($kanbanGroup, $ongoingExecutions) = $tester->project->getStats4Kanban();

r(count($kanbanGroup))          && p() && e('2');
r(count($kanbanGroup['other'])) && p() && e('3');
r(count($kanbanGroup['my']))    && p() && e('3');
r(count($ongoingExecutions))    && p() && e('1');

r($kanbanGroup['other'][2]['wait'][0])  && p('status') && e('wait');
r($kanbanGroup['other'][4]['doing'][0]) && p('status') && e('doing');
r($kanbanGroup['my'][1]['wait'][0])     && p('status') && e('wait');
r($kanbanGroup['my'][3]['doing'][0])    && p('status') && e('doing');
r($kanbanGroup['my'][5]['doing'][0])    && p('status') && e('doing');
r($ongoingExecutions[6])                && p('status') && e('doing');
