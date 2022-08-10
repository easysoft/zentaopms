#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::createBranch();
cid=1
pid=1

使用空的gitlabID,projectID,分支对象创建GitLab分支 >> return false
使用空的gitlabID、projectID,正确的分支对象创建GitLab分支 >> return false
使用正确的gitlabID、分支信息，错误的projectID创建分支 >> return false
通过gitlabID,projectID,分支对象正确创建GitLab分支 >> return true
使用重复的分支信息创建分支 >> return false

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;

$result = $gitlab->createBranch($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID,projectID,分支对象创建GitLab分支

dao::$errors = array();
$_POST['branch'] = 'test_branch17';
$_POST['ref']    = 'master';
$result = $gitlab->createBranch($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用空的gitlabID、projectID,正确的分支对象创建GitLab分支

dao::$errors = array();
$gitlabID    = 1;
$result      = $gitlab->createBranch($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的gitlabID、分支信息，错误的projectID创建分支

dao::$errors = array();
$projectID   = 1555;

/* Delete branches with the same name. */
$apiRoot = $gitlab->getApiRoot($gitlabID);
$url     = sprintf($apiRoot, "/projects/{$projectID}/repository/branches/{$_POST['branch']}");
commonModel::http($url, array(), $options = array(CURLOPT_CUSTOMREQUEST => 'DELETE'));

$result = $gitlab->createBranch($gitlabID, $projectID);
if($result === true) $result = 'return true';
r($result) && p() && e('return true');  //通过gitlabID,projectID,分支对象正确创建GitLab分支

$result = $gitlab->createBranch($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用重复的分支信息创建分支