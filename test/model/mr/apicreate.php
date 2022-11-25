#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 mrModel::apiCreate();
cid=0
pid=0

使用空的RepoUrl数据创建 >> fail
使用正确的RepoUrl,错误的分支数据创建mr请求 >> fail
使用源分支和目标分支一样的数据创建mr请求 >> success
使用正确的数据创建mr请求 >> success

*/

global $lang;
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
$_POST['data']['DiffMsg']        = '';
$_POST['data']['RepoSrcBranch']  = '';
$_POST['data']['RepoDistBranch'] = '';
$_POST['data']['MergeStatus']    = '1';
$result = $mrModel->apiCreate();
if(!$result) $result = 'fail';
r($result) && p() && e('fail'); //使用正确的RepoUrl,错误的分支数据创建mr请求

dao::$errors = array();
$_POST['data']['RepoSrcBranch']  = 'master';
$_POST['data']['RepoDistBranch'] = 'master';

/* Get same opened MR and close it.*/
$gitlabID  = 1;
$projectID = 42;
$oldMR     = $mrModel->apiGetSameOpened($gitlabID, $projectID, 'master', $projectID, 'master');
if($oldMR) $mrModel->apiCloseMR(1, 42, $oldMR->iid);

$result = $mrModel->apiCreate();
if(!$result and dao::isError())
{
    $errors = dao::getError();
    if($errors[0] == $lang->mr->errorLang[1]) $result = 'success';
}
r($result) && p() && e('success'); //使用源分支和目标分支一样的数据创建mr请求

dao::$errors = array();
$_POST['data']['RepoSrcBranch']  = 'branch-08';
$_POST['data']['RepoDistBranch'] = 'master';
$oldMR = $mrModel->apiGetSameOpened($gitlabID, $projectID, 'branch-08', $projectID, 'master');
if($oldMR) $mrModel->apiCloseMR(1, 42, $oldMR->iid);

$result = $mrModel->apiCreate();
if(dao::isError())
{
    $errors = dao::getError();
    $result = preg_match('/[存在另外一个同样的合并请求在源项目分支中,存在重复并且未关闭的合并请求]: ID([0-9]+)/', $errors[0], $matches); //检查错误原因是否是已存在一样的mr请求
}
if($result) $result = 'success';
r($result) && p() && e('success'); //使用正确的数据创建mr请求