#!/usr/bin/env php
<?php

/**

title=测试 userModel::getProjects();
timeout=0
cid=19630

- 执行userTest模块的getProjectsTest方法，参数是''  @0
- 执行userTest模块的getProjectsTest方法，参数是'nonexistuser'  @0
- 执行userTest模块的getProjectsTest方法，参数是'admin'  @5
- 执行userTest模块的getProjectsTest方法，参数是'admin' 第1条的status属性 @wait
- 执行userTest模块的getProjectsTest方法，参数是'admin', 'wait'  @2
- 执行userTest模块的getProjectsTest方法，参数是'admin' 第1条的executionCount属性 @0
- 执行userTest模块的getProjectsTest方法，参数是'admin' 第1条的storyCount属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

global $tester;
$tester->dao->delete()->from(TABLE_PROJECT)->exec();
$tester->dao->delete()->from(TABLE_TEAM)->exec();

for($i = 1; $i <= 5; $i++)
{
    $project = new stdClass();
    $project->id = $i;
    $project->project = 0;
    $project->type = 'project';
    $project->name = "项目{$i}";
    $project->begin = '2023-01-01';
    $project->end = '2024-12-31';
    $project->status = $i <= 2 ? 'wait' : ($i == 3 ? 'doing' : ($i == 4 ? 'suspended' : 'closed'));
    $project->deleted = '0';
    $project->vision = 'rnd';
    $tester->dao->insert(TABLE_PROJECT)->data($project)->exec();
}

for($i = 1; $i <= 5; $i++)
{
    $team = new stdClass();
    $team->root = $i;
    $team->type = 'project';
    $team->account = 'admin';
    $team->role = 'dev';
    $tester->dao->insert(TABLE_TEAM)->data($team)->exec();
}

su('admin');

$userTest = new userTest();

r(count($userTest->getProjectsTest(''))) && p() && e('0');
r(count($userTest->getProjectsTest('nonexistuser'))) && p() && e('0');
r(count($userTest->getProjectsTest('admin'))) && p() && e('5');
r($userTest->getProjectsTest('admin')) && p('1:status') && e('wait');
r(count($userTest->getProjectsTest('admin', 'wait'))) && p() && e('2');
r($userTest->getProjectsTest('admin')) && p('1:executionCount') && e('0');
r($userTest->getProjectsTest('admin')) && p('1:storyCount') && e('0');