#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::createGroup();
timeout=0
cid=16638

- 步骤1：空名称测试第name条的0属性 @群组名称不能为空
- 步骤2：空路径测试第path条的0属性 @群组URL不能为空
- 步骤3：正常创建测试 @1
- 步骤4：无效gitlabID测试 @0
- 步骤5：普通用户权限测试 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';
su('admin');

zenData('pipeline')->gen(5);

$gitlab = new gitlabTest();

$gitlabID = 1;
$group = new stdclass();
$group->name = '';
$group->path = 'unit_test_group';
$group->description = 'unit test group description';
$group->visibility = 'public';
$group->request_access_enabled = '1';
$group->lfs_enabled = '1';
$group->project_creation_level = 'developer';
$group->subgroup_creation_level = 'maintainer';

r($gitlab->createGroupTest($gitlabID, $group)) && p('name:0') && e('群组名称不能为空'); // 步骤1：空名称测试

$group->name = 'unitTestGroup';
$group->path = '';
r($gitlab->createGroupTest($gitlabID, $group)) && p('path:0') && e('群组URL不能为空'); // 步骤2：空路径测试

$group->path = 'unit_test_group';
$result = $gitlab->createGroupTest($gitlabID, $group);
if(!empty($result[0]) and $result[0] == '保存失败，群组URL路径已经被使用。') $result = true;
r($result) && p() && e('1'); // 步骤3：正常创建测试

$invalidGitlabID = 999;
r($gitlab->createGroupTest($invalidGitlabID, $group)) && p() && e('0'); // 步骤4：无效gitlabID测试

su('user');
$group->path = 'unit_test_group_user';
r($gitlab->createGroupTest($gitlabID, $group)) && p() && e('0'); // 步骤5：普通用户权限测试