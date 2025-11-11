#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::buildSearchForm();
timeout=0
cid=0

- 步骤1: 测试普通产品queryID为0属性queryID @0
- 步骤2: 测试普通产品actionURL设置属性actionURL @/release/browse
- 步骤3: 测试分支产品有分支配置
 - 属性hasBranchConfig @1
 - 属性branchCount @3
- 步骤4: 测试平台产品分支和构建配置
 - 属性branchCount @3
 - 属性buildCount @2
- 步骤5: 测试普通产品无分支配置属性branchCount @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/releasezen.unittest.class.php';

su('admin');

$releaseTest = new releaseZenTest();

// 准备不同类型的产品对象
$normalProduct = new stdClass();
$normalProduct->id = 1;
$normalProduct->type = 'normal';

$branchProduct = new stdClass();
$branchProduct->id = 2;
$branchProduct->type = 'branch';

$platformProduct = new stdClass();
$platformProduct->id = 3;
$platformProduct->type = 'platform';

r($releaseTest->buildSearchFormTest(0, '/release/browse', $normalProduct, '')) && p('queryID') && e('0'); // 步骤1: 测试普通产品queryID为0
r($releaseTest->buildSearchFormTest(1, '/release/browse', $normalProduct, '')) && p('actionURL') && e('/release/browse'); // 步骤2: 测试普通产品actionURL设置
r($releaseTest->buildSearchFormTest(2, '/release/browse', $branchProduct, '1')) && p('hasBranchConfig,branchCount') && e('1,3'); // 步骤3: 测试分支产品有分支配置
r($releaseTest->buildSearchFormTest(3, '/release/browse', $platformProduct, '2')) && p('branchCount,buildCount') && e('3,2'); // 步骤4: 测试平台产品分支和构建配置
r($releaseTest->buildSearchFormTest(0, '/release/browse', $normalProduct, '')) && p('branchCount') && e('0'); // 步骤5: 测试普通产品无分支配置