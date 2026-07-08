#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 providerModel::getReposByProviderID();
timeout=0
cid=0

- 步骤1：providerID=1 且不看镜像时返回 4 个未删除代码库 @4
- 步骤2：providerID=1 且只看镜像时返回 1 个镜像代码库 @1
- 步骤3：providerID=2 时返回 1 个代码库 @1
- 步骤4：providerID 不存在时返回空数组 @0
- 步骤5：providerID=0 时返回空数组 @0
- 步骤6：providerID=1 且不看镜像时返回结果按 id 建索引 @1

*/

// 用官方 ops_repo.sql 建/重置表（不含 uk_gitUID 唯一键），规避 base 库缺列或多余唯一键问题。
global $dbh, $app;
$dataRoot = $app->getAppRoot() . 'test' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
if(file_exists($dataRoot . 'ops_repo.sql')) $dbh->exec(file_get_contents($dataRoot . 'ops_repo.sql'));

zenData('ops_repo')->loadYaml('ops_repo_getreposbyproviderid')->gen(6);

su('admin');

$providerTester = new providerModelTest();

r(count($providerTester->getReposByProviderIDTest(1, false))) && p() && e('4'); // 步骤1：providerID=1 非镜像视角
r(count($providerTester->getReposByProviderIDTest(1, true))) && p() && e('1');  // 步骤2：providerID=1 只看镜像
r(count($providerTester->getReposByProviderIDTest(2, false))) && p() && e('1'); // 步骤3：providerID=2
r(count($providerTester->getReposByProviderIDTest(999, false))) && p() && e('0'); // 步骤4：不存在的服务
r(count($providerTester->getReposByProviderIDTest(0, false))) && p() && e('0');   // 步骤5：providerID=0
r(isset($providerTester->getReposByProviderIDTest(1, false)[1])) && p() && e('1'); // 步骤6：结果按 id 建索引
