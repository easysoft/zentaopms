#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getPairsByIdList();
cid=1
pid=1

查找ID为0、11、12、13的项目数量 >> 3
查找所有项目数量 >> 110

*/

global $tester;
$tester->loadModel('project');
$projectIdList = array(0, 11, 12, 13);

r(count($tester->project->getPairsByIdList($projectIdList))) && p() && e('3');   //查找ID为0、11、12、13的项目数量
r(count($tester->project->getPairsByIdList(array())))        && p() && e('110'); //查找所有项目数量