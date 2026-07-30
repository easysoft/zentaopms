#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::updateCodePath();
timeout=0
cid=0

- 步骤 1：查询不存在的仓库会产生 dao 错误 @1
- 步骤 2：查询不存在的仓库时 updateCodePath 返回 false @0
- 步骤 3：查询不存在的仓库时返回值类型为 bool @bool
- 步骤 4：查询不存在的仓库时归一化后的计数为 0 @0
- 步骤 5：再次查询另一个不存在的仓库仍返回 false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$missingRepoID = 999999;

r($gitfoxTest->updateCodePathErrorTest($missingRepoID, 1)) && p() && e('1');
r($gitfoxTest->updateCodePathTest($missingRepoID, 1)) && p() && e('0');
r($gitfoxTest->updateCodePathTypeTest($missingRepoID, 1)) && p() && e('bool');
r($gitfoxTest->updateCodePathCountTest($missingRepoID, 1)) && p() && e('0');
r($gitfoxTest->updateCodePathTest($missingRepoID + 1, 1)) && p() && e('0');
