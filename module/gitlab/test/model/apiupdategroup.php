#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiUpdateGroup();
timeout=0
cid=1

- 使用空的groupID创建gitlab群组 @0
- 使用错误gitlabID创建群组 @0
- 通过gitlabID,用户对象正确更新用户名字属性description @apiUpdatedGroup

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

r($gitlab->apiUpdateGroup($gitlabID, $group)) && p() && e('0'); //使用空的groupID创建gitlab群组
r($gitlab->apiUpdateGroup(0, $group))         && p() && e('0'); //使用错误gitlabID创建群组

$group->id = $groupID;
r($gitlab->apiUpdateGroup($gitlabID, $group)) && p('description') && e('apiUpdatedGroup'); //通过gitlabID,用户对象正确更新用户名字