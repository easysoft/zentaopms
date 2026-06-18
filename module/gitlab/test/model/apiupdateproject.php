#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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

$gitlab    = new gitlabModelTest();
$gitlabID  = 1;
$projectID = 18;

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

$result = $gitlab->apiUpdateProjectTest($gitlabID, $emptyProject);
if($result === false) $result = '0';
r($result) && p() && e('0');

$result = $gitlab->apiUpdateProjectTest(0, $invalidProject);
if($result === false) $result = '0';
r($result) && p() && e('0');

r($gitlab->apiUpdateProjectTest($gitlabID, $validProject)) && p('description') && e('apiUpdatedProject');
r($gitlab->apiUpdateProjectTest($gitlabID, $multiAttrProject)) && p('name') && e('Updated Project Name');
r($gitlab->apiUpdateProjectTest($gitlabID, $nonExistentProject)) && p() && e('0');
