#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getPairsByModel();
cid=1
pid=1

获取项目集1下的所有项目数量 >> 9
获取项目集1下的项目名称 >> 项目集1 / 项目11
获取项目集1下的所有瀑布项目数量 >> 3

*/

global $tester;
$tester->loadModel('project');

r(count($tester->project->getPairsByModel('all', 1)))       && p()     && e('9');                // 获取项目集1下的所有项目数量
r($tester->project->getPairsByModel('all', 1))              && p('21') && e('项目集1 / 项目11'); // 获取项目集1下的项目名称
r(count($tester->project->getPairsByModel('waterfall', 1))) && p()     && e('3');                // 获取项目集1下的所有瀑布项目数量