#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试 gitlabModel::createGroup();
timeout=0
cid=1

- 使用空的名字创建gitlab群组第name条的0属性 @群组名称不能为空
- 使用空的群组URL创建gitlab群组第path条的0属性 @群组URL不能为空
- 通过gitlabID,projectID,分支对象正确创建GitLab分支 @1

*/

zdTable('pipeline')->gen(5);

$gitlab = new gitlabTest();

$gitlabID  = 1;
$group = new stdclass();
$group->name                    = '';
$group->path                    = 'unit_test_group17';
$group->description             = 'unit_test_group desc';
$group->visibility              = 'public';
$group->request_access_enabled  = '1';
$group->lfs_enabled             = '1';
$group->project_creation_level  = 'developer';
$group->subgroup_creation_level = 'maintainer';

r($gitlab->createGroupTest($gitlabID, $group)) && p('name:0') && e('群组名称不能为空'); //使用空的名字创建gitlab群组

$group->name = 'unitTestGroup17';
$group->path = '';
r($gitlab->createGroupTest($gitlabID, $group)) && p('path:0') && e('群组URL不能为空'); //使用空的群组URL创建gitlab群组

$group->path = 'unit_test_group17';
$result = $gitlab->createGroupTest($gitlabID, $group);
if(!empty($result[0]) and $result[0] == '保存失败，群组URL路径已经被使用。') $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确创建GitLab分支