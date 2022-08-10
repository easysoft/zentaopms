#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::createBranchPriv();
cid=1
pid=1

使用空的gitlabID,projectID,保护分支对象创建GitLab保护分支 >> return false
使用空的gitlabID、projectID,正确的保护分支对象创建GitLab保护分支 >> return false
使用正确的gitlabID、保护分支信息，错误的projectID创建保护分支 >> return false
通过gitlabID,projectID,保护分支对象正确创建GitLab保护分支 >> return true
使用重复的保护分支信息创建保护分支 >> return false
使用保护分支信息更新保护分支 >> return true

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;

$result = $gitlab->createBranchPriv($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,保护分支对象创建GitLab保护分支

dao::$errors = array();
$_POST['name']               = 'master';
$_POST['merge_access_level'] = '40';
$_POST['push_access_level']  = '40';
$result = $gitlab->createBranchPriv($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID、projectID,正确的保护分支对象创建GitLab保护分支

dao::$errors = array();
$gitlabID    = 1;
$result      = $gitlab->createBranchPriv($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的gitlabID、保护分支信息，错误的projectID创建保护分支

dao::$errors = array();
$projectID   = 1565;
$result      = $gitlab->createBranchPriv($gitlabID, $projectID);
if($result === true) $result = 'return true';
r($result) && p() && e('return true');  //通过gitlabID,projectID,保护分支对象正确创建GitLab保护分支

$result = $gitlab->createBranchPriv($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用重复的保护分支信息创建保护分支

dao::$errors = array();
$_POST['merge_access_level'] = '0';
$result = $gitlab->createBranchPriv($gitlabID, $projectID, $branch = 'master');
if($result === true) $result = 'return true';
r($result) && p() && e('return true'); //使用保护分支信息更新保护分支