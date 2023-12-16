#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiCreateProject();
timeout=0
cid=1

- 使用空的名字创建gitlab群组 @return false
- 使用空的群组URL创建gitlab群组 @return false
- 使用错误gitlabID创建群组 @0
- 通过gitlabID,projectID,分支对象正确创建GitLab分支 @1
- 使用重复的分支信息创建分支属性message @Failed to save project {:path=>["已经被使用"]}

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$project = new stdclass();
$project->name         = '';
$project->path         = 'unit_test_project17';
$project->description  = 'unit_test_project desc';
$project->visibility   = 'public';
$project->namespace_id = '1';

$result = $gitlab->apiCreateProject($gitlabID, $project);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的名字创建gitlab群组

$project->name = 'unitTestProject17';
$project->path = '';
$result = $gitlab->apiCreateProject($gitlabID, $project);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的群组URL创建gitlab群组

r($gitlab->apiCreateProject(0, $project)) && p() && e('0'); //使用错误gitlabID创建群组

$project->path = 'unit_test_project17';
$result = $gitlab->apiCreateProject($gitlabID, $project);
if(!empty($result->name) and $result->name == $project->name) $result = true;
if(!empty($result->message->path[0]) and $result->message->path[0] == '已经被使用') $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确创建GitLab分支

$result = $gitlab->apiCreateProject($gitlabID, $project);
r($result->message) && p('path:0') && e('已经被使用'); //使用重复的分支信息创建分支
