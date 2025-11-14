#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiUpdateGroup();
timeout=0
cid=16628

- 执行gitlab模块的apiUpdateGroup方法，参数是$gitlabID, $emptyGroup  @0
- 执行gitlab模块的apiUpdateGroup方法，参数是0, $invalidGroup  @0
- 执行gitlab模块的apiUpdateGroup方法，参数是$gitlabID, $invalidGroup 属性description @apiUpdatedGroup
- 执行gitlab模块的apiUpdateGroup方法，参数是$gitlabID, $validGroup 属性name @Updated Group Name
- 执行gitlab模块的apiUpdateGroup方法，参数是$gitlabID, $nonExistentGroup  @~~

*/

zenData('pipeline')->gen(5);

global $app;
$app->rawModule = 'gitlab';
$app->rawMethod = 'browse';

$gitlab = $tester->loadModel('gitlab');

$gitlabID = 1;

/* Create test group first. */
$group = new stdclass();
$group->name                    = 'unitTestGroup99';
$group->path                    = 'unit_test_group99';
$group->description             = 'unit_test_group desc';
$group->visibility              = 'public';
$group->request_access_enabled  = '1';
$group->lfs_enabled             = '1';
$group->project_creation_level  = 'developer';
$group->subgroup_creation_level = 'maintainer';
$gitlab->apiCreateGroup($gitlabID, $group);

/* Get groupID. */
$gitlabGroups = $gitlab->apiGetGroups($gitlabID);
$groupID = 0;
foreach($gitlabGroups as $gitlabGroup)
{
    if($gitlabGroup->name == 'unitTestGroup99')
    {
        $groupID = $gitlabGroup->id;
        break;
    }
}

/* Test cases. */
$emptyGroup = new stdclass();
$emptyGroup->description = 'test description';

$invalidGroup = new stdclass();
$invalidGroup->id = $groupID;
$invalidGroup->description = 'apiUpdatedGroup';

$validGroup = new stdclass();
$validGroup->id = $groupID;
$validGroup->name = 'Updated Group Name';
$validGroup->description = 'Updated description';

$nonExistentGroup = new stdclass();
$nonExistentGroup->id = 888888;
$nonExistentGroup->description = 'Non-existent group';

r($gitlab->apiUpdateGroup($gitlabID, $emptyGroup)) && p() && e('0');
r($gitlab->apiUpdateGroup(0, $invalidGroup)) && p() && e('0');
r($gitlab->apiUpdateGroup($gitlabID, $invalidGroup)) && p('description') && e('apiUpdatedGroup');
r($gitlab->apiUpdateGroup($gitlabID, $validGroup)) && p('name') && e('Updated Group Name');
r($gitlab->apiUpdateGroup($gitlabID, $nonExistentGroup)) && p() && e('~~');