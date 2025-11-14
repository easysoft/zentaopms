#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('project')->gen(5);

/**

title=测试 programModel::checkAccess;
timeout=0
cid=17804

- 不传入ID @10
- 传入存在ID的值 @11
- 不传入ID，读取session信息 @11
- 传入正确的ID @14
- 传入不存在的ID @0

*/

global $tester;
$tester->loadModel('project');

$projects = array(10 => 10, 11 => 11, 14 => 14);
$idList   = array(0, 11, 14, 16);
r($tester->project->checkAccess($idList[0], $projects)) && p() && e('10'); //不传入ID
r($tester->project->checkAccess($idList[1], $projects)) && p() && e('11'); //传入存在ID的值
r($tester->project->checkAccess($idList[0], $projects)) && p() && e('11'); //不传入ID，读取session信息
r($tester->project->checkAccess($idList[2], $projects)) && p() && e('14'); //传入正确的ID
r($tester->project->checkAccess($idList[3], $projects)) && p() && e('0'); //传入不存在的ID