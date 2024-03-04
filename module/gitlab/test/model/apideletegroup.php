#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiDeleteGroup();
timeout=0
cid=1

- 使用空的groupID删除gitlab群组 @0
- 使用错误gitlabID删除群组 @0
- 通过gitlabID,projectID,分支对象正确删除GitLab群组属性message @202 Accepted

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 1;

/* Create group. */
$group = new stdclass();
$group->name                    = 'unitTestGroup17';
$group->path                    = 'unit_test_group17';
$group->description             = 'unit_test_group desc';
$group->visibility              = 'public';
$group->request_access_enabled  = '1';
$group->lfs_enabled             = '1';
$group->project_creation_level  = 'developer';
$group->subgroup_creation_level = 'maintainer';
$gitlab->apiCreateGroup($gitlabID, $group);

/* Get groupID. */
$gitlabGroups = $gitlab->apiGetGroups($gitlabID);
foreach($gitlabGroups as $gitlabGroup)
{
    if($gitlabGroup->name == 'unitTestGroup17')
    {
        $groupID = $gitlabGroup->id;
        break;
    }
}

$group = new stdclass();
$group->description = 'apiUpdatedGroup';

r($gitlab->apiDeleteGroup($gitlabID, 0)) && p() && e('0'); //使用空的groupID删除gitlab群组
r($gitlab->apiDeleteGroup(0, $groupID))         && p() && e('0'); //使用错误gitlabID删除群组

r($gitlab->apiDeleteGroup($gitlabID, $groupID)) && p('message') && e('202 Accepted');         //通过gitlabID,projectID,分支对象正确删除GitLab群组