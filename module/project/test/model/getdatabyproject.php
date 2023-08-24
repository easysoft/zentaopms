#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zdTable('project')->config('execution')->gen(100);
zdTable('task')->gen(100);
zdTable('user')->gen(100);
zdTable('build')->gen(50);

/**

title=测试 projectTao::getDataByProject
timeout=0
cid=1

- 获取ID为11的项目的下的执行ID属性id @101

- 获取ID为60的项目的下的任务ID属性id @50

- 获取ID为20的项目的下的版本ID属性id @10

*/

global $tester;
$tester->loadModel('project');

r($tester->project->getDataByProject('zt_project', 11, 'sprint')) && p('id') && e('101'); // 获取ID为11的项目的下的执行ID
r($tester->project->getDataByProject('zt_task', 60))              && p('id') && e('50');  // 获取ID为60的项目的下的任务ID
r($tester->project->getDataByProject('zt_build', 20))             && p('id') && e('10');  // 获取ID为20的项目的下的版本ID