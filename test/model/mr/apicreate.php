#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::apiCreate();
cid=0
pid=0

使用空的RepoUrl数据创建                    >> fail
使用正确的RepoUrl,错误的分支数据创建mr请求 >> fail
使用源分支和目标分支一样的数据创建mr请求   >> success 
使用正确的数据创建mr请求                   >> success

*/

$mrModel = $tester->loadModel('mr');

$_POST = array(
    'data' => array()
);

$_POST['data']['RepoUrl'] = '';
$result = $mrModel->apiCreate();
if(!$result) $result = 'fail';
r($result) && p() && e('fail'); //使用空的RepoUrl数据创建

dao::$errors = array();
$_POST['data']['RepoUrl']        = 'http://192.168.1.161:51080/root/azalea723test.git';
$_POST['data']['DiffMsg']  = '';
$_POST['data']['RepoSrcBranch']  = '';
$_POST['data']['RepoDistBranch'] = '';
$_POST['data']['MergeStatus']    = '1';
$result = $mrModel->apiCreate();
if(!$result) $result = 'fail';
r($result) && p() && e('fail'); //使用正确的RepoUrl,错误的分支数据创建mr请求

dao::$errors = array();
$_POST['data']['RepoSrcBranch']  = 'master';
$_POST['data']['RepoDistBranch'] = 'master';
$result = $mrModel->apiCreate();
if($result) $result = 'success';
r($result) && p() && e('success'); //使用源分支和目标分支一样的数据创建mr请求

dao::$errors = array();
$_POST['data']['RepoSrcBranch']  = 'branch-08';
$_POST['data']['RepoDistBranch'] = 'master';
$result = $mrModel->apiCreate();
if($result) $result = 'success';
r($result) && p() && e('success'); //使用正确的数据创建mr请求
