#!/usr/bin/env php
<?php
/**

title=测试 projectModel->getPairsByIdList();
timeout=0
cid=17838

- 查找ID为0、11、12、13的项目数量 @3
- 查找所有敏捷项目数量 @18
- 查找所有瀑布项目数量 @18
- 查找所有看板项目数量 @18
- 查找所有项目数量 @90

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zenData('project')->gen(100);

global $tester;
$tester->loadModel('project');
$projectIdList = array(0, 11, 12, 13);

r(count($tester->project->getPairsByIdList($projectIdList)))       && p() && e('3');  // 查找ID为0、11、12、13的项目数量
r(count($tester->project->getPairsByIdList(array(), 'scrum')))     && p() && e('18'); // 查找所有敏捷项目数量
r(count($tester->project->getPairsByIdList(array(), 'waterfall'))) && p() && e('18'); // 查找所有瀑布项目数量
r(count($tester->project->getPairsByIdList(array(), 'kanban')))    && p() && e('18'); // 查找所有看板项目数量
r(count($tester->project->getPairsByIdList(array())))              && p() && e('90'); // 查找所有项目数量
