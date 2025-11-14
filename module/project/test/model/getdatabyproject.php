#!/usr/bin/env php
<?php
/**

title=测试 projectTao::getDataByProject
timeout=0
cid=17822

- 获取ID为11的项目的下的执行ID属性id @101
- 获取ID为60的项目的下的任务ID属性id @50
- 获取ID为20的项目的下的版本ID属性id @10
- 获取ID为20的项目的下的BugID属性id @28
- 获取ID为20的项目的下的设计ID属性id @17

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('project')->loadYaml('execution')->gen(100);
zenData('task')->gen(100);
zenData('user')->gen(100);
zenData('build')->gen(50);
zenData('bug')->gen(30);
zenData('design')->gen(20);

global $tester;
$tester->loadModel('project');

r($tester->project->getDataByProject('zt_project', 11, 'sprint')) && p('id') && e('101'); // 获取ID为11的项目的下的执行ID
r($tester->project->getDataByProject('zt_task', 60))              && p('id') && e('50');  // 获取ID为60的项目的下的任务ID
r($tester->project->getDataByProject('zt_build', 20))             && p('id') && e('10');  // 获取ID为20的项目的下的版本ID
r($tester->project->getDataByProject('zt_bug', 20))               && p('id') && e('28');  // 获取ID为20的项目的下的BugID
r($tester->project->getDataByProject('zt_design', 45))            && p('id') && e('17');  // 获取ID为20的项目的下的设计ID
