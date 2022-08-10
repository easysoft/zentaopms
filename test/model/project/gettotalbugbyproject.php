#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getTotalBugByProject();
cid=1
pid=1

获取id为11的项目下激活的bug数量 >> 4

*/

global $tester;
$tester->loadModel('project');

$projectIdList = array(11, 12, 13, 14, 15, 16, 27);

r($tester->project->getTotalBugByProject($projectIdList, 'active')) && p('11') && e('4'); //获取id为11的项目下激活的bug数量
r($tester->project->getTotalBugByProject($projectIdList, 'active')) && p('27') && e('');  //获取id为27的项目下激活的bug数量