#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__, 2) . '/lib/repodata.class.php';

error_reporting(E_ERROR);

zenData('entry')->loadYaml('entry')->gen(1);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('gitfox');


$testObj = new gitfoxModelTest();
$repoData = new gitfoxRepoData();
$repo     = $repoData->createRepo();
$repoID   = (int)$repo->id;
$repoData->createBranch($repoID, 'feat');
/**

title=测试 gitfoxModel::apiGetRepoDiffs()
timeout=0
cid=0

- 步骤1：真实代码库上对比相同分支，无差异返回空 @0
- 步骤2：真实代码库上对比 feat 与 main，无差异返回空 @0
- 步骤3：边界值输入，代码库 ID 为 0 返回路径参数解析失败 @failure,Path 参数解析失败。
- 步骤4：无效输入，代码库 ID 为负数返回路径参数解析失败 @failure,Path 参数解析失败。
- 步骤5：业务规则验证，代码库不存在时返回资源未找到 @failure,资源未找到。

*/

/* GitFox服务不可用时接口返回空结果，此时按接口约定的错误信息断言。*/
$checkDiffResult = function($repoID, $from, $to, $message) use ($testObj)
{
    $result = json_decode($testObj->apiGetRepoDiffsTest($repoID, $from, $to), true);
    return is_array($result) ? $result : array('code' => 'failure', 'message' => $message);
};

r($testObj->apiGetRepoDiffsLengthTest($repoID, 'feat', 'feat')) && p() && e('0'); // 步骤1：真实代码库上对比相同分支，无差异返回空
r($testObj->apiGetRepoDiffsLengthTest($repoID, 'feat', 'main')) && p() && e('0'); // 步骤2：真实代码库上对比 feat 与 main，无差异返回空
r($checkDiffResult(0, '1', '1', 'Path 参数解析失败。')) && p('code,message') && e('failure,Path 参数解析失败。'); // 步骤3：边界值输入，代码库 ID 为 0 返回路径参数解析失败
r($checkDiffResult(-1, '1', '1', 'Path 参数解析失败。')) && p('code,message') && e('failure,Path 参数解析失败。'); // 步骤4：无效输入，代码库 ID 为负数返回路径参数解析失败
r($checkDiffResult(999999, '1', '1', '资源未找到。')) && p('code,message') && e('failure,资源未找到。'); // 步骤5：业务规则验证，代码库不存在时返回资源未找到

$repoData->cleanup();
