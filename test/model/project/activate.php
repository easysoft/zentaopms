#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::activate();
cid=1
pid=1

开始id为66状态不是closed的项目 >> 1
开始id为67状态是closed的项目 >> 0

*/

$project = new Project('admin');

$beginId = array(66, 67);

r($project->checkStatus($beginId[0])) && p() && e('1'); //开始id为66状态不是closed的项目
r($project->checkStatus($beginId[1])) && p() && e('0'); //开始id为67状态是closed的项目
system("./ztest init");