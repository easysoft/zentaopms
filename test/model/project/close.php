#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';

/**

title=测试 projectModel::close();
cid=1
pid=1

关闭id为20状态不是closed的项目 >> 1
关闭id为26状态是closed的项目 >> 0
关闭id为41状态是suspended的项目 >> 0

*/

$project = new Project('admin');

$closeId = array(20, 26, 41);
$realEnd1 = array('realEnd' => '2022-03-11');

//var_dump($project->checkStatusOff($closeId[0], $realEnd1));die;
r($project->checkStatusOff($closeId[0], $realEnd1)) && p() && e('1'); //关闭id为20状态不是closed的项目
r($project->checkStatusOff($closeId[1], $realEnd1)) && p() && e('0'); //关闭id为26状态是closed的项目
r($project->checkStatusOff($closeId[2], $realEnd1)) && p() && e('0'); //关闭id为41状态是suspended的项目
system("./ztest init");