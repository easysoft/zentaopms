#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::apiGetBranches();
timeout=0
cid=16683

- 步骤1：无效gogsID @0
- 步骤2：空项目参数 @0
- 步骤3：无效ID和空项目 @0
- 步骤4：无效服务器ID @0
- 步骤5：有效参数但API调用失败 @0
- 步骤6：测试不同项目参数 @0
- 步骤7：负数ID测试 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gogs.unittest.class.php';

// 2. zendata数据准备
$table = zenData('pipeline');
$table->id->range('1-10');
$table->name->range('Gogs测试服务器{3},无效服务器{2}');
$table->type->range('gogs{3},gitlab{2}');
$table->url->range('http://gogs.test.com{2},http://invalid.com{1},,{2}');
$table->token->range('valid_token_123{2},invalid_token{1},,{2}');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$gogsTest = new gogsTest();

// 5. 执行测试步骤（至少5个）
r($gogsTest->apiGetBranchesTest(0, 'test/project')) && p() && e('0'); // 步骤1：无效gogsID
r($gogsTest->apiGetBranchesTest(1, '')) && p() && e('0'); // 步骤2：空项目参数
r($gogsTest->apiGetBranchesTest(0, '')) && p() && e('0'); // 步骤3：无效ID和空项目
r($gogsTest->apiGetBranchesTest(999, 'invalid/project')) && p() && e('0'); // 步骤4：无效服务器ID
r($gogsTest->apiGetBranchesTest(5, 'easycorp/unittest')) && p() && e('0'); // 步骤5：有效参数但API调用失败
r($gogsTest->apiGetBranchesTest(1, 'test/repo')) && p() && e('0'); // 步骤6：测试不同项目参数
r($gogsTest->apiGetBranchesTest(-1, 'test/project')) && p() && e('0'); // 步骤7：负数ID测试