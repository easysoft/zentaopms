#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::editProject();
timeout=0
cid=1

- 使用空的projectID创建gitlab群组 @0
- 使用错误gitlabID创建群组 @0
- 通过gitlabID,用户对象正确更新项目描述属性description @apiUpdatedProject

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;

/* Create project. */
$project = new stdclass();
$project->name         = 'unitTestProject17';
$project->path         = 'unit_test_project17';
$project->description  = 'unit_test_project editproject unit test';
$project->visibility   = 'public';
$project->namespace_id = '1';
$gitlab->apiCreateProject($gitlabID, $project);

/* Get projectID. */
$gitlabProjects = $gitlab->apiGetProjects($gitlabID);
foreach($gitlabProjects as $gitlabProject)
{
    if($gitlabProject->name == 'unitTestProject17')
    {
        $projectID = $gitlabProject->id;
        break;
    }
}

$project = new stdclass();
$project->description = 'editProject';

$gitlabTest = new gitlabTest();
r($gitlabTest->editProjectTest($gitlabID, $project)) && p('name:0') && e('项目名称不能为空'); //使用空的projectID创建gitlab群组

$project->name = 'unitTestProject17';
$project->id   = $projectID;
r($gitlabTest->editProjectTest($gitlabID, $project)) && p() && e('1'); //通过gitlabID,用户对象正确更新项目描述
