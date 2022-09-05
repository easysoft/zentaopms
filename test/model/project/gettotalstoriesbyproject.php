#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel::getTotalStoriesByProject;
cid=1
pid=1

不传入产品ID列表，获取项目11下的所有需求数量 >> 4
传入产品ID列表，获取项目11下的所有需求数量 >> 4;4
*/

global $tester;
$tester->loadModel('project');

$projectIdList = array(12,13);
$stories1 = $tester->project->getTotalStoriesByProject(11);
$stories2 = $tester->project->getTotalStoriesByProject($projectIdList);

r($stories1[11]) && p('allStories') && e('4');                    // 不传入产品ID列表，获取项目11下的所有需求数量
r($stories2)     && p('12:allStories;13:allStories') && e('4;4'); // 传入产品ID列表，获取项目11下的所有需求数量
