#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 gitlabModel::createTagPriv();
cid=1
pid=1

使用空的gitlabID,projectID,保护标签对象创建GitLab保护标签 >> 标签不能为空
使用正确的保护标签对象，空的gitlabID、projectID创建GitLab保护标签 >> return false
使用正确的gitlabID、保护标签信息，错误的projectID创建保护标签 >> 404 Project Not Found
使用正确的gitlabID、projectID和保护标签信息创建保护标签 >> return true
使用正确的gitlabID、projectID、保护标签信息，编辑保护标签 >> return true

*/

$gitlab = $tester->loadModel('gitlab');

$gitlabID  = 0;
$projectID = 0;

$gitlab->createTagPriv($gitlabID, $projectID);
r(dao::getError()) && p('name:0') && e('标签不能为空'); //使用空的gitlabID,projectID,保护标签对象创建GitLab保护标签

$_POST['name']                = 'test_tag1';
$_POST['create_access_level'] = '40';
$result = $gitlab->createTagPriv($gitlabID, $projectID);
if($result === false) $result = 'return false';
r($result) && p() && e('return false'); //使用正确的保护标签对象，空的gitlabID、projectID创建GitLab保护标签

dao::getError();
$gitlabID  = 1;
$projectID = 1;
$gitlab->createTagPriv($gitlabID, $projectID);
r(dao::getError()[0]) && p() && e('404 Project Not Found'); //使用正确的gitlabID、保护标签信息，错误的projectID创建保护标签

$projectID = 1555;
$gitlab->apiDeleteTagPriv($gitlabID, $projectID, $_POST['name']);
$result = $gitlab->createTagPriv($gitlabID, $projectID);
if(dao::getError() == false) $result = 'return true';
r($result) && p() && e('return true'); //使用正确的gitlabID、projectID和保护标签信息创建保护标签

$projectID = 1555;
$result = $gitlab->createTagPriv($gitlabID, $projectID, $_POST['name']);
if(dao::getError() == false) $result = 'return true';
r($result) && p() && e('return true'); //使用正确的gitlabID、projectID、保护标签信息，编辑保护标签