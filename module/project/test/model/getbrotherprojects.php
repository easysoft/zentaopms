#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');
$project = zenData('project')->gen(100);

/**

title=测试 projectModel->getBrotherProjects();
timeout=0
cid=17816

- 获取项目id为79同一个项目集下的所有项目个数 @9

- 获取项目id为100同一个项目集下的所有项目个数 @9

- 获取项目id为79同一个项目集下的兄弟项目id
 - 属性19 @19
 - 属性29 @29
 - 属性39 @39

- 获取项目id为100同一个项目集下的兄弟项目id
 - 属性20 @20
 - 属性30 @30
 - 属性40 @40

*/

global $tester;
$tester->loadModel('project');

$project79  = $tester->project->getById(79);
$project100 = $tester->project->getById(100);

r(count($tester->project->getBrotherProjects($project79)))  && p() && e('9'); // 获取项目id为79同一个项目集下的所有项目个数
r(count($tester->project->getBrotherProjects($project100))) && p() && e('9'); // 获取项目id为100同一个项目集下的所有项目个数
r($tester->project->getBrotherProjects($project79))         && p('19,29,39') && e('19,29,39'); // 获取项目id为79同一个项目集下的兄弟项目id
r($tester->project->getBrotherProjects($project100))        && p('20,30,40') && e('20,30,40'); // 获取项目id为100同一个项目集下的兄弟项目id