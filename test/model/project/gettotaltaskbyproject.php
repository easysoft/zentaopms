#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getTotalTaskByProject();
cid=1
pid=1

获取id为11的项目下激活的task数量 >> 14
获取id为15的项目下完成的task数量 >> 2
获取id为27的项目下激活的task数量 >> 8

*/

global $tester;
$tester->loadModel('project');

$projectIdList = array(11, 12, 13, 14, 15, 16, 27);

r($tester->project->getTotalTaskByProject($projectIdList, 'undone')) && p('11') && e('14'); // 获取id为11的项目下激活的task数量
r($tester->project->getTotalTaskByProject($projectIdList, 'done'))   && p('15') && e('2');  // 获取id为15的项目下完成的task数量
r($tester->project->getTotalTaskByProject($projectIdList, 'undone')) && p('27') && e('8');  // 获取id为27的项目下激活的task数量