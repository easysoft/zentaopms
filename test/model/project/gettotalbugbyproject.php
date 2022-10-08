#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getTotalBugByProject();
cid=1
pid=1

获取id为11的项目下激活的bug数量 >> 4
获取id为27的项目下激活的bug数量 >> 1

*/

global $tester;
$tester->loadModel('project');

$projectIdList = array(11, 12, 13, 14, 15, 16, 27);
$result = $tester->project->getTotalBugByProject($projectIdList, 'active');

r($result[11]) && p('allBugs') && e('4');  //获取id为11的项目下激活的bug数量
r($result[27]) && p('allBugs') && e('1');  //获取id为27的项目下激活的bug数量
