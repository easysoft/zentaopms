#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

/**

title=测试 projectModel->activate();
cid=1
pid=1

激活id为4的项目 >> object
激活id为5的项目 >> object

*/

global $tester;
$tester->loadModel('project');
$project = new Project();
$data = new stdClass();

$data->status       = 'doing';
$data->begin        = '2022-10-10';
$data->end          = '2022-10-10';
$data->status       = 'doing';
$data->comment      = 'fgasgqasfdgasfgasg';
$data->readjustTime = 1;
$data->readjustTask = 1;

r($project->activate(1287, $data)) && p('1:field,old,new') && e('status,closed,doing');    // 激活id为1287状态是closed的项目
r($project->activate(1288, $data)) && p('1:field,old,new') && e('status,suspended,doing'); // 激活id为1288状态是suspended的项目

