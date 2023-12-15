#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->setProject();
timeout=0
cid=1

- 测试设置project。属性name @test_project

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$projectId = 1;

$project = new stdclass();
$project->id   = 1;
$project->name = 'test_project';

r($gitlab->setProjectTest($gitlabID = 1, $projectId, $project)) && p('name') && e('test_project'); // 测试设置project。