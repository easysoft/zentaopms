#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::start();
cid=1
pid=1

开始id为81状态是suspended的项目(代码缺失elBegan参数，报警告) >> 1
开始id为83状态是wait的项目 >> 1
开始id为82状态是closed的项目 >> 0
开始id为85状态是doing的项目 >> 0

*/

$project = new Project('admin');

$statusId = array(81, 83, 82, 85);

r($project->checkStatusBegin($statusId[0])) && p() && e('1'); // 开始id为81状态是suspended的项目(代码缺失elBegan参数，报警告)
r($project->checkStatusBegin($statusId[1])) && p() && e('1'); // 开始id为83状态是wait的项目
r($project->checkStatusBegin($statusId[2])) && p() && e('0'); // 开始id为82状态是closed的项目
r($project->checkStatusBegin($statusId[3])) && p() && e('0'); // 开始id为85状态是doing的项目