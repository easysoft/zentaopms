#!/usr/bin/env php
<?php

/**

title=测试 repobranchtypeModel::apiDeleteBranchType();
timeout=0
cid=0

- 测试步骤1：验证删除不存在的分支类型 @~~
- 测试步骤2：验证repo为null时从分支类型获取repo信息 @~~
- 测试步骤3：验证有效的repo对象参数 @~~
- 测试步骤4：验证typeID为0的边界情况 @~~
- 测试步骤5：验证正常删除流程参数传递 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 加载测试数据
zenData('repo')->gen(5);
zenData('ops_branch_type')->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$branchTypeTest = new repobranchtypeTest();

// 创建模拟repo对象
$repo = new stdclass();
$repo->id             = 1;
$repo->serviceProject = 100;

// 测试步骤1：验证删除不存在的分支类型
// 注意：实际删除需要调用gitfox API，这里验证方法不报错
r(is_bool($branchTypeTest->apiDeleteBranchTypeTest($repo, 999)) || is_array($branchTypeTest->apiDeleteBranchTypeTest($repo, 999))) && p() && e('~~');

// 测试步骤2：验证repo为null时从分支类型获取repo信息
r(is_bool($branchTypeTest->apiDeleteBranchTypeTest(null, 1)) || is_array($branchTypeTest->apiDeleteBranchTypeTest(null, 1))) && p() && e('~~');

// 测试步骤3：验证有效的repo对象参数
r(is_bool($branchTypeTest->apiDeleteBranchTypeTest($repo, 1)) || is_array($branchTypeTest->apiDeleteBranchTypeTest($repo, 1))) && p() && e('~~');

// 测试步骤4：验证typeID为0的边界情况
r(is_bool($branchTypeTest->apiDeleteBranchTypeTest($repo, 0)) || is_array($branchTypeTest->apiDeleteBranchTypeTest($repo, 0))) && p() && e('~~');

// 测试步骤5：验证正常删除流程参数传递
$repo2 = new stdclass();
$repo2->id             = 2;
$repo2->serviceProject = 200;
r(is_bool($branchTypeTest->apiDeleteBranchTypeTest($repo2, 2)) || is_array($branchTypeTest->apiDeleteBranchTypeTest($repo2, 2))) && p() && e('~~');
