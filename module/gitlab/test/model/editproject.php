#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';
su('admin');

/**

title=测试 gitlabModel::editProject();
timeout=0
cid=1

- 使用空的projectID创建gitlab群组第name条的0属性 @项目名称不能为空
- 通过gitlabID,用户对象正确更新项目描述 @1

*/

zenData('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID    = 1;
$projectName = 'unitest' . time();

/* Create project. */
$project = new stdclass();
$project->name         = $projectName;
$project->path         = $projectName;
$project->description  = 'unit_test_project editproject unit test';
$project->visibility   = 'public';
$project->namespace_id = '1';
$gitlab->apiCreateProject($gitlabID, $project);

/* Get projectID. */
$gitlabProjects = $gitlab->apiGetProjects($gitlabID);
foreach($gitlabProjects as $gitlabProject)
{
    if($gitlabProject->name == $projectName)
    {
        $projectID = $gitlabProject->id;
        break;
    }
}

$project = new stdclass();
$project->description = 'editProject';

$gitlabTest = new gitlabTest();
r($gitlabTest->editProjectTest($gitlabID, $project)) && p('name:0') && e('项目名称不能为空'); //使用空的projectID创建gitlab群组

$project->name = $projectName;
$project->id   = $projectID;
r($gitlabTest->editProjectTest($gitlabID, $project)) && p() && e('1'); //通过gitlabID,用户对象正确更新项目描述
