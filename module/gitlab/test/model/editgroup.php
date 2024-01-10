#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::editGroup();
timeout=0
cid=1

- 使用空的groupID创建gitlab群组第name条的0属性 @群组名称不能为空
- 通过gitlabID,用户对象正确更新用户名字 @1

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

$gitlabTest = new gitlabTest();
r($gitlabTest->editGroupTest($gitlabID, $group)) && p('name:0') && e('群组名称不能为空'); //使用空的groupID创建gitlab群组

$group->id   = $groupID;
$group->name = 'unitTestGroup17';
r($gitlabTest->editGroupTest($gitlabID, $group)) && p() && e('1'); //通过gitlabID,用户对象正确更新用户名字