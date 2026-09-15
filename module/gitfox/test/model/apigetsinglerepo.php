#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetSingleRepo();
timeout=0
cid=0

- 步骤 1：查询真实代码库返回代码库名称
- 步骤 2：查询真实代码库返回代码库 uid
- 步骤 3：查询不存在的代码库返回值类型为 array @array
- 步骤 4：查询不存在的代码库返回空结果 @0
- 步骤 5：再次查询另一个不存在的代码库仍返回空结果 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__, 2) . '/lib/repodata.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$repoData   = new gitfoxRepoData();
$repo       = $repoData->createRepo(array('name' => 'unittest-repo'));
$repoID     = (int)$repo->id;
$missingRepoID = 999999;

r($gitfoxTest->apiGetSingleRepoNameTest($repoID)) && p() && e('unittest-repo'); // 步骤 1：查询真实代码库返回代码库名称
r($gitfoxTest->apiGetSingleRepoUIDLengthTest($repoID)) && p() && e('42');      // 步骤 2：查询真实代码库返回代码库 uid
r($gitfoxTest->apiGetSingleRepoTypeTest($missingRepoID)) && p() && e('array'); // 步骤 3：查询不存在的代码库返回值类型为 array
r($gitfoxTest->apiGetSingleRepoCountTest($missingRepoID)) && p() && e('0');    // 步骤 4：查询不存在的代码库返回空结果
r($gitfoxTest->apiGetSingleRepoCountTest($missingRepoID + 1)) && p() && e('0'); // 步骤 5：再次查询另一个不存在的代码库仍返回空结果

$repoData->cleanup();
