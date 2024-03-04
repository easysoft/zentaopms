#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::createProject();
timeout=0
cid=1

- 使用空的名字创建gitlab项目第name条的0属性 @项目名称不能为空
- 使用空的群组URL创建gitlab项目第path条的0属性 @项目标识串不能为空
- 通过gitlabID,projectID,分支对象正确创建GitLab项目 @1

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$gitlabID  = 1;
$project = new stdclass();
$project->name         = '';
$project->path         = 'unit_test_project17';
$project->description  = 'unit_test_project desc';
$project->visibility   = 'public';
$project->namespace_id = '1';

r($gitlab->createProjectTest($gitlabID, $project)) && p('name:0') && e('项目名称不能为空'); //使用空的名字创建gitlab项目

$project->name = 'unitTestProject17';
$project->path = '';
r($gitlab->createProjectTest($gitlabID, $project)) && p('path:0') && e('项目标识串不能为空'); //使用空的群组URL创建gitlab项目

$project->path = 'unit_test_project17';
$result = $gitlab->createProjectTest($gitlabID, $project);
if(!empty($result['name'][0]) and $result['name'][0] == '已经被使用') $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确创建GitLab项目