#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::createTag();
cid=1
pid=1

使用空的gitlabID,projectID,标签对象创建GitLab标签 >> return false
使用空的gitlabID、projectID,正确的标签对象创建GitLab标签 >> return false
使用正确的gitlabID、标签信息，错误的projectID创建标签 >> return false
通过gitlabID,projectID,标签对象正确创建GitLab标签 >> return true
使用重复的标签信息创建标签 >> return false

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;

$result = $gitlab->createTag($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,标签对象创建GitLab标签

dao::$errors = array();
$_POST['tag_name'] = 'test_tag17';
$_POST['ref']      = 'master';
$result = $gitlab->createTag($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID、projectID,正确的标签对象创建GitLab标签

dao::$errors = array();
$gitlabID    = 1;
$result      = $gitlab->createTag($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的gitlabID、标签信息，错误的projectID创建标签

dao::$errors = array();
$projectID   = 1555;

/* Delete tags with the same name. */
$gitlab->apiDeleteTagPriv($gitlabID, $projectID, $_POST['tag_name']);
$gitlab->apiDeleteTag($gitlabID, $projectID, $_POST['tag_name']);

$result = $gitlab->createTag($gitlabID, $projectID);
if($result === true) $result = 'return true';
r($result) && p() && e('return true');  //通过gitlabID,projectID,标签对象正确创建GitLab标签

$result = $gitlab->createTag($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用重复的标签信息创建标签