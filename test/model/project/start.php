#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';
su('admin');

/**

title=测试 projectModel->start();
cid=1
pid=1

开始id为81状态是suspended的项目 >> 项目71,doing
开始id为82状态是closed的项目 >> 0
开始id为83状态是wait的项目 >> 项目73,doing
开始id为85状态是doing的项目 >> 0

*/

$project = new Project();

r($project->start(81)) && p('name,status') && e('项目71,doing'); // 开始id为81状态是suspended的项目
r($project->start(82)) && p()              && e('0');            // 开始id为82状态是closed的项目
r($project->start(83)) && p('name,status') && e('项目73,doing'); // 开始id为83状态是wait的项目
r($project->start(85)) && p()              && e('0');            // 开始id为85状态是doing的项目
