#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::create();
cid=0
pid=0

使用空的repoID, gitlabID。创建mr。 >> 『GitLab』不能为空。
使用正确的repoID, gitlabID。POST数据正确 或者错误原因为已存在一样的mr请求 >> success
使用源分支和目标分支一样的数据mr请求 >> 通过API创建合并请求失败，失败原因：源项目分支与目标项目分支不能相同

*/

$mrModel = $tester->loadModel('mr');

$_POST = array();

$_POST['gitlabID']      = 0;
$_POST['repoID']        = 0;
$_POST['sourceProject'] = 42;
$_POST['sourceBranch']  = 'branch-08';
$_POST['targetProject'] = 42;
$_POST['targetBranch']  = 'branch-09';
$result = $mrModel->create();
r($result) && p('message[gitlabID]:0') && e('『GitLab』不能为空。'); //使用空的repoID, gitlabID。创建mr。

$_POST['gitlabID']           = 1;
$_POST['title']              = 'test_create';
$_POST['description']        = 'test_create';
$_POST['repoID']             = 1;
$_POST['assignee']           = '';
$_POST['removeSourceBranch'] = '1';
$result = $mrModel->create();
if($result['result'] == 'success') $result = 'success';
$result = preg_match('/存在另外一个同样的合并请求在源项目分支中: ID([0-9]+)/', $result['message'], $matches); //检查错误原因是否是已存在一样的mr请求
if($result) $result = 'success';
r($result) && p() && e('success'); //使用正确的repoID, gitlabID。POST数据正确 或者错误原因为已存在一样的mr请求

$_POST['targetBranch'] = 'branch-08';
$result = $mrModel->create();
r($result) && p('message') && e('通过API创建合并请求失败，失败原因：源项目分支与目标项目分支不能相同'); //使用源分支和目标分支一样的数据mr请求
