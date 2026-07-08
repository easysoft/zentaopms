#!/usr/bin/env php
<?php

/**

title=测试 repobranchtypeModel::getBranchTypeByRepoID();
timeout=0
cid=0

- 测试步骤1：获取仓库ID为1的分支类型列表，验证数量 @1
- 测试步骤2：获取第一个分支类型的基本信息
 - 属性name @branch_type1
 - 属性key @branch_type1
- 测试步骤3：验证第二个分支类型的基本信息
 - 属性name @branch_type2
 - 属性key @branch_type2
- 测试步骤4：获取不存在的repoID，返回空数组 @0
- 测试步骤5：获取repoID为0的系统级分支类型 @0
- 测试步骤6：验证prefixes字段被正确解析为数组 @dev/

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 加载测试数据
zenData('ops_branch_type')->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$branchTypeTest = new repobranchtypeTest();

// 测试步骤1：获取仓库ID为1的分支类型列表，验证数量
r(count($branchTypeTest->getBranchTypeByRepoIDTest(1))) && p() && e('1');

// 测试步骤2：获取第一个分支类型的基本信息
r($branchTypeTest->getBranchTypeByRepoIDTest(1)) && p('1:name,key') && e('branch_type1,branch_type1');

// 测试步骤3：验证repo=2的分支类型信息
r($branchTypeTest->getBranchTypeByRepoIDTest(2)) && p('2:name,key') && e('branch_type2,branch_type2');

// 测试步骤4：获取不存在的repoID，返回空数组
r(count($branchTypeTest->getBranchTypeByRepoIDTest(999))) && p() && e('0');

// 测试步骤5：获取repoID为0的系统级分支类型（默认数据中不存在）
r(count($branchTypeTest->getBranchTypeByRepoIDTest(0))) && p() && e('0');

// 测试步骤6：验证prefixes字段被正确解析为数组
r($branchTypeTest->getBranchTypeByRepoIDTest(1)) && p('1:prefix', '|') && e('dev/');
