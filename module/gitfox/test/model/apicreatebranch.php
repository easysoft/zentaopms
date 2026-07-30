#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiCreateBranch();
timeout=0
cid=0

- 步骤 1：name 为空 apiCreateBranch 返回空对象 @0
- 步骤 2：source 为空 apiCreateBranch 返回空对象 @0
- 步骤 3：不存在的仓库调用 apiCreateBranch 不产生 dao 错误 @0
- 步骤 4：不存在的仓库返回 failure 和资源未找到 @failure,资源未找到。
- 步骤 5：不存在的仓库返回值类型为 object @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$model = $gitfoxTest->instance;
$missingRepoID = 999999;

$emptyName  = (object)array('name' => '', 'source' => 'main');
$emptySource = (object)array('name' => 'test', 'source' => '');
$valid = (object)array('name' => 'feature/test', 'source' => 'main');

r($gitfoxTest->apiCreateBranchCountTest(1, $emptyName)) && p() && e('0');
r($gitfoxTest->apiCreateBranchCountTest(1, $emptySource)) && p() && e('0');
r($gitfoxTest->apiCreateBranchErrorTest($missingRepoID, $valid)) && p() && e('0');
r($gitfoxTest->apiCreateBranchTest($missingRepoID, $valid)) && p('code,message') && e('failure,资源未找到。');
r($gitfoxTest->apiCreateBranchResultTypeTest($missingRepoID, $valid)) && p() && e('object');
