#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel::getTotalStoriesByProject;
cid=1
pid=1

不传入产品ID列表，获取项目11下的所有需求数量 >> 0
传入产品ID列表，获取项目11下的所有需求数量 >> 2

*/

global $tester;
$tester->loadModel('project');

$productIdList = array(1, 2, 3);
$stories1 = $tester->project->getTotalStoriesByProject(11);
$stories2 = $tester->project->getTotalStoriesByProject(11, $productIdList);

r($stories1) && p() && e('0'); // 不传入产品ID列表，获取项目11下的所有需求数量
r($stories2) && p() && e('2'); // 传入产品ID列表，获取项目11下的所有需求数量