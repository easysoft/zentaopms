#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiDeleteProject();
timeout=0
cid=1

- 使用空的projectID删除项目 @0
- 使用错误gitlabID删除项目 @0
- 通过gitlabID,项目id正确删除项目属性message @202 Accepted

*/

zenData('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;
$projectName = 'unitTestProject' . time();

/* Create project. */
$project = new stdclass();
$project->name         = $projectName;
$project->path         = $projectName;
$project->description  = 'unit_test_project desc';
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
$project->description = 'apiUpdatedProject';

r($gitlab->apiDeleteProject($gitlabID, 0))  && p() && e('0'); //使用空的projectID删除项目
r($gitlab->apiDeleteProject(0, $projectID)) && p() && e('0'); //使用错误gitlabID删除项目

r($gitlab->apiDeleteProject($gitlabID, $projectID)) && p('message') && e('202 Accepted');         //通过gitlabID,项目id正确删除项目
