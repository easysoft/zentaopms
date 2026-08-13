#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetRepos();
timeout=0
cid=0

- 步骤 1：默认查询 apiGetRepos 不产生 dao 错误 @0
- 步骤 2：默认查询 apiGetRepos 返回值类型为 array @array
- 步骤 3：不存在的仓库关键字不产生 dao 错误 @0
- 步骤 4：不存在的仓库关键字返回 0 条记录 @0
- 步骤 5：带关键字查询时返回值类型仍为 array @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$missingRepo = 'missing-repo-' . uniqid();

r($gitfoxTest->apiGetReposErrorTest()) && p() && e('0');
r($gitfoxTest->apiGetReposTypeTest()) && p() && e('array');
r($gitfoxTest->apiGetReposErrorTest($missingRepo)) && p() && e('0');
r($gitfoxTest->apiGetReposCountTest($missingRepo)) && p() && e('0');
r($gitfoxTest->apiGetReposTypeTest($missingRepo)) && p() && e('array');
