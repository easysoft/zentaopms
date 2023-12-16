#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiCreateGroup();
timeout=0
cid=1

- 使用空的名字创建gitlab群组 @return false
- 使用空的群组URL创建gitlab群组 @return false
- 使用错误gitlabID创建群组 @0
- 通过gitlabID,projectID,分支对象正确创建GitLab分支 @1
- 使用重复的分支信息创建分支属性message @Failed to save group {:path=>["已经被使用"]}

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

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

$result = $gitlab->apiCreateGroup($gitlabID, $group);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的名字创建gitlab群组

$group->name = 'unitTestGroup17';
$group->path = '';
$result = $gitlab->apiCreateGroup($gitlabID, $group);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的群组URL创建gitlab群组

r($gitlab->apiCreateGroup(0, $group)) && p() && e('0'); //使用错误gitlabID创建群组

$group->path = 'unit_test_group17';
$result = $gitlab->apiCreateGroup($gitlabID, $group);
if(!empty($result->name) and $result->name == $group->name) $result = true;
if(!empty($result->message) and $result->message == 'Failed to save group {:path=>["已经被使用"]}') $result = true;
r($result) && p() && e('1');         //通过gitlabID,projectID,分支对象正确创建GitLab分支
r($gitlab->apiCreateGroup($gitlabID, $group)) && p('message') && e('Failed to save group {:path=>["已经被使用"]}'); //使用重复的分支信息创建分支
