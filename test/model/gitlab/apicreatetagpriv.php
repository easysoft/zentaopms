#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::apiCreateTagPriv();
cid=1
pid=1

使用空的gitlabID,projectID,保护标签对象创建GitLab保护标签 >> return false
使用正确的gitlabID,空的projectID、保护标签对象创建GitLab保护标签 >> return false
使用正确的gitlabID，空的保护标签信息，错误的projectID创建保护标签 >> return false
使用正确的gitlabID、保护标签信息，错误的projectID创建保护标签 >> 404 Project Not Found
使用正确的gitlabID、projectID、保护标签信息创建保护标签 >> test_tag1
使用重复的保护标签信息创建保护标签 >> 名称已经被使用

*/

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

$projectID = 1;
$result    = $gitlab->apiCreateTagPriv($gitlabID, $projectID, $tag);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的gitlabID，空的保护标签信息，错误的projectID创建保护标签

$tag->name                = 'test_tag1';
$tag->create_access_level = '40';
r($gitlab->apiCreateTagPriv($gitlabID, $projectID, $tag)) && p('message') && e('404 Project Not Found'); //使用正确的gitlabID、保护标签信息，错误的projectID创建保护标签

$projectID = 1555;
$gitlab->apiDeleteTagPriv($gitlabID, $projectID, $tag->name);
r($gitlab->apiCreateTagPriv($gitlabID, $projectID, $tag)) && p('name')      && e('test_tag1'); //使用正确的gitlabID、projectID、保护标签信息创建保护标签
r($gitlab->apiCreateTagPriv($gitlabID, $projectID, $tag)) && p('message:0') && e("名称已经被使用"); //使用重复的保护标签信息创建保护标签