#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetSingleRepo();
timeout=0
cid=0

- 步骤 1：命中缓存时返回缓存仓库 @cached-repo
- 步骤 2：查询不存在的仓库会产生 dao 错误 @1
- 步骤 3：查询不存在的仓库返回空结果 @0
- 步骤 4：查询不存在的仓库返回值类型为 array @array
- 步骤 5：再次查询另一个不存在的仓库仍返回空结果 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model      = $gitfoxTest->instance;
$missingRepoID = 999999;

$cached = new stdclass();
$cached->id   = 99;
$cached->name = 'cached-repo';
$gitfoxTest->setRepoCache(99, $cached);

r($model->apiGetSingleRepo(99)) && p('name') && e('cached-repo');
r($gitfoxTest->apiGetSingleRepoErrorTest($missingRepoID)) && p() && e('1');
r($gitfoxTest->apiGetSingleRepoCountTest($missingRepoID)) && p() && e('0');
r($gitfoxTest->apiGetSingleRepoTypeTest($missingRepoID)) && p() && e('array');
r($gitfoxTest->apiGetSingleRepoCountTest($missingRepoID + 1)) && p() && e('0');
