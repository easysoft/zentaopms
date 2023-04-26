#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->activate();
cid=1
pid=1

激活id为20的项目 >> object
激活id为20的项目 >> object

*/

global $tester;
$tester->loadModel('project');

$project = new Project();

$data1 = array(
    'id' => 2,
    'begin'=> '2023-04-26',
    'end'=> '10001-01-07',
    'readjustTime'=> 1,
    'readjustTask'=> 1,
    'status'=> 'doing',
    'comment'=> 'sdfsdf'
);

$data2 = array(
    'id' => 3,
    'begin'=> '2023-04-26',
    'end'=> '10001-01-07',
    'readjustTime'=> 1,
    'readjustTask'=> 1,
    'status'=> 'doing',
    'comment'=> 'sdfsdf'
);

r($project->activate($data1)) && p('1:field,old,new') && e('status,closed,doing');    // 激活id为2状态是closed的项目
r($project->activate($data2)) && p('1:field,old,new') && e('status,suspended,doing'); // 激活id为3状态是suspended的项目

