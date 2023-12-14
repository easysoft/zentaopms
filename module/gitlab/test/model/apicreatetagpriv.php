#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::apiCreateTagPriv();
timeout=0
cid=1

- 使用空的gitlabID,projectID,保护标签对象创建GitLab保护标签 @return false
- 使用正确的gitlabID,空的projectID、保护标签对象创建GitLab保护标签 @return false
- 使用正确的gitlabID，空的保护标签信息，错误的projectID创建保护标签 @return false
- 使用正确的gitlabID、保护标签信息，错误的projectID创建保护标签属性message @404 Project Not Found
- 使用正确的gitlabID、projectID、保护标签信息创建保护标签属性name @tag1
- 使用重复的保护标签信息创建保护标签第message条的0属性 @名称 已经被使用

*/

zdTable('pipeline')->gen(5);

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;
$tag       = new stdclass();

$result = $gitlab->apiCreateTagPriv($gitlabID, $projectID, $tag);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,保护标签对象创建GitLab保护标签

$gitlabID = 1;
$result   = $gitlab->apiCreateTagPriv($gitlabID, $projectID, $tag);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的gitlabID,空的projectID、保护标签对象创建GitLab保护标签

$projectID = 1555;
$result    = $gitlab->apiCreateTagPriv($gitlabID, $projectID, $tag);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的gitlabID，空的保护标签信息，错误的projectID创建保护标签

$tag->name                = 'tag1';
$tag->create_access_level = '40';
r($gitlab->apiCreateTagPriv($gitlabID, $projectID, $tag)) && p('message') && e('404 Project Not Found'); //使用正确的gitlabID、保护标签信息，错误的projectID创建保护标签

$projectID = 2;
$gitlab->apiDeleteTagPriv($gitlabID, $projectID, $tag->name);
r($gitlab->apiCreateTagPriv($gitlabID, $projectID, $tag)) && p('name')      && e('tag1'); //使用正确的gitlabID、projectID、保护标签信息创建保护标签
r($gitlab->apiCreateTagPriv($gitlabID, $projectID, $tag)) && p('message:0') && e("名称 已经被使用"); //使用重复的保护标签信息创建保护标签