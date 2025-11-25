#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiUpdateProject();
timeout=0
cid=16631

- 执行gitlab模块的apiUpdateProject方法，参数是$gitlabID, $emptyProject  @0
- 执行gitlab模块的apiUpdateProject方法，参数是0, $invalidProject  @0
- 执行gitlab模块的apiUpdateProject方法，参数是$gitlabID, $validProject 属性description @apiUpdatedProject
- 执行gitlab模块的apiUpdateProject方法，参数是$gitlabID, $multiAttrProject 属性name @Updated Project Name
- 执行gitlab模块的apiUpdateProject方法，参数是$gitlabID, $nonExistentProject  @~~

*/

zenData('pipeline')->gen(5);

global $app;
$app->rawModule = 'gitlab';
$app->rawMethod = 'browse';

$gitlab = $tester->loadModel('gitlab');

$gitlabID = 1;

/* Create test project first. */
$project = new stdclass();
$project->name         = 'unitTestProject99';
$project->path         = 'unit_test_project99';
$project->description  = 'unit_test_project desc';
$project->visibility   = 'public';
$project->namespace_id = '1';
$gitlab->apiCreateProject($gitlabID, $project);

/* Get projectID. */
$gitlabProjects = $gitlab->apiGetProjects($gitlabID);
$projectID = 0;
foreach($gitlabProjects as $gitlabProject)
{
    if($gitlabProject->name == 'unitTestProject99')
    {
        $projectID = $gitlabProject->id;
        break;
    }
}

/* Test cases. */
$emptyProject = new stdclass();
$emptyProject->description = 'test description';

$invalidProject = new stdclass();
$invalidProject->id = $projectID;
$invalidProject->description = 'apiUpdatedProject';

$validProject = new stdclass();
$validProject->id = $projectID;
$validProject->description = 'apiUpdatedProject';

$multiAttrProject = new stdclass();
$multiAttrProject->id = $projectID;
$multiAttrProject->name = 'Updated Project Name';
$multiAttrProject->description = 'Updated description';

$nonExistentProject = new stdclass();
$nonExistentProject->id = 888888;
$nonExistentProject->description = 'Non-existent project';

r($gitlab->apiUpdateProject($gitlabID, $emptyProject)) && p() && e('0');
r($gitlab->apiUpdateProject(0, $invalidProject)) && p() && e('0');
r($gitlab->apiUpdateProject($gitlabID, $validProject)) && p('description') && e('apiUpdatedProject');
r($gitlab->apiUpdateProject($gitlabID, $multiAttrProject)) && p('name') && e('Updated Project Name');
r($gitlab->apiUpdateProject($gitlabID, $nonExistentProject)) && p() && e('~~');