#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 gitlabModel::createTag();
timeout=0
cid=1

- 使用空的gitlabID,projectID,标签对象创建GitLab标签 @return false
- 使用空的gitlabID、projectID,正确的标签对象创建GitLab标签 @return false
- 使用正确的gitlabID、标签信息，错误的projectID创建标签 @return false
- 通过gitlabID,projectID,标签对象正确创建GitLab标签 @return true
- 使用重复的标签信息创建标签 @return false

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;
$tag       = (object)array('tag_name' => 'test_tag17', 'ref' => 'master');
$emptyTag  = new stdclass();

$result = $gitlab->createTag($gitlabID, $projectID, $emptyTag);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,标签对象创建GitLab标签

dao::$errors = array();
$result = $gitlab->createTag($gitlabID, $projectID, $tag);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID、projectID,正确的标签对象创建GitLab标签

dao::$errors = array();
$gitlabID    = 1;
$result      = $gitlab->createTag($gitlabID, $projectID, $tag);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的gitlabID、标签信息，错误的projectID创建标签

dao::$errors = array();
$projectID   = 2;

/* Delete tags with the same name. */
$gitlab->apiDeleteTagPriv($gitlabID, $projectID, $tag->tag_name);
$gitlab->apiDeleteTag($gitlabID, $projectID, $tag->tag_name);

$result = $gitlab->createTag($gitlabID, $projectID, $tag);
if($result === true) $result = 'return true';
r($result) && p() && e('return true');  //通过gitlabID,projectID,标签对象正确创建GitLab标签

$result = $gitlab->createTag($gitlabID, $projectID, $tag);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用重复的标签信息创建标签