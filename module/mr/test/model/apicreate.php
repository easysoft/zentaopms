#!/usr/bin/env php
<?php

/**

title=测试 mrModel::apiCreate();
timeout=0
cid=0

- 不存在的代码库ID @No matched gitlab.
- 源分支为空，流水线为空情况
 - 第sourceBranch条的0属性 @『源分支』不能为空。
 - 第jobID条的0属性 @『流水线任务』不能为空。
- 源分支与目标分支相同 @源项目分支与目标项目分支不能相同
- 流水线为空情况第jobID条的0属性 @『流水线任务』不能为空。
- 已存在一样的mr请求 @存在重复并且未关闭的合并请求: ID1
- 正确的数据 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

zdTable('pipeline')->gen(1);
zdTable('repo')->config('repo')->gen(1);
zdTable('mr')->config('mr')->gen(1);

$mrModel = new mrTest();

/* Post params. */
$params = array(
    'repoID'       => 0,
    'sourceBranch' => '',
    'targetBranch' => 'master',
    'diffs'        => '',
    'jobID'        => 0,
    'mergeStatus'  => 0
);

r($mrModel->apiCreateTester($params)) && p('0') && e('No matched gitlab.'); // 不存在的代码库ID

$params['repoID'] = 1;
r($mrModel->apiCreateTester($params)) && p('sourceBranch:0;jobID:0') && e('『源分支』不能为空。,『流水线任务』不能为空。'); // 源分支为空，流水线为空情况

$params['sourceBranch'] = 'master';
r($mrModel->apiCreateTester($params)) && p('0') && e('源项目分支与目标项目分支不能相同'); // 源分支与目标分支相同

$params['sourceBranch'] = 'test';
r($mrModel->apiCreateTester($params)) && p('jobID:0') && e('『流水线任务』不能为空。'); // 流水线为空情况

$params['jobID']        = 1;
$params['sourceBranch'] = 'test1';
r($mrModel->apiCreateTester($params)) && p('0') && e('存在重复并且未关闭的合并请求: ID1'); // 已存在一样的mr请求

$params['sourceBranch'] = 'test2';
r($mrModel->apiCreateTester($params)) && p() && e('1'); // 正确的数据
