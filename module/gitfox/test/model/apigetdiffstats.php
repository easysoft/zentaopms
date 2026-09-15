#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigetdiffstats();
timeout=0
cid=0

- 步骤 1：真实代码库返回差值统计对象 @object
- 步骤 2：真实代码库返回合并基线 @1
- 步骤 3：真实代码库不产生 dao 错误 @0
- 步骤 4：不存在的代码库返回 false @0
- 步骤 5：不存在的代码库返回值类型为 bool @bool

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__, 2) . '/lib/repodata.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$repoData   = new gitfoxRepoData();
$repo       = $repoData->createRepo();
$repoID     = (int)$repo->id;
$repoData->createBranch($repoID, 'feat');

r($gitfoxTest->apiGetDiffStatsTypeTest($repoID, 'feat', 'main')) && p() && e('object'); // 步骤 1：真实代码库返回差值统计对象
r($gitfoxTest->apiGetDiffStatsHasMergeBaseTest($repoID, 'feat', 'main')) && p() && e('1'); // 步骤 2：真实代码库返回合并基线
r($gitfoxTest->apiGetDiffStatsErrorTest($repoID, 'feat', 'main')) && p() && e('0'); // 步骤 3：真实代码库不产生 dao 错误
r($gitfoxTest->apiGetDiffStatsTest(999999, 'feat', 'main')) && p() && e('0'); // 步骤 4：不存在的代码库返回 false
r($gitfoxTest->apiGetDiffStatsTypeTest(999999, 'feat', 'main')) && p() && e('bool'); // 步骤 5：不存在的代码库返回值类型为 bool

$repoData->cleanup();
