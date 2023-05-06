#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('1')->gen(8);

/**

title=测试 projectTao::getProjectsStats()
timeout=0
cid=1
- 执行$projects['myProjects'] @3

- 执行$projects['otherProjects'] @3

- 执行projects['myProjects'][1]['wait'][0]模块的status方法 @wait

- 执行projects['myProjects'][3]['doing'][0]模块的status方法 @doing

- 执行projects['otherProjects'][2]['wait'][0]模块的status方法 @wait

- 执行projects['otherProjects'][4]['doing'][0]模块的status方法 @doing
*/

global $tester;
$tester->loadModel('project');
$projects = $tester->project->getProjectsStats();

r(count($projects['myProjects']))                    && p() && e('3');
r(count($projects['otherProjects']))                 && p() && e('3');
r($projects['myProjects'][1]['wait'][0]->status)     && p() && e('wait');
r($projects['myProjects'][3]['doing'][0]->status)    && p() && e('doing');
r($projects['otherProjects'][2]['wait'][0]->status)  && p() && e('wait');
r($projects['otherProjects'][4]['doing'][0]->status) && p() && e('doing');
