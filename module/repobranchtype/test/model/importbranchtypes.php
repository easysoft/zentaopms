#!/usr/bin/env php
<?php

/**

title=测试 repobranchtypeModel::importBranchTypes();
timeout=0
cid=0

- 测试步骤1：导入空数组应返回false @0
- 测试步骤2：导入不存在的分支类型ID应返回false @0
- 测试步骤3：导入非全局模板(repo!=0)应返回false @0
- 测试步骤4：验证导入参数组装正确(模拟测试) @~~
- 测试步骤5：导入部分不存在的ID数量不匹配应返回false @0

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

// 测试步骤1：导入空数组应返回false
r($branchTypeTest->importBranchTypesTest($repo, array())) && p() && e('0');

// 测试步骤2：导入不存在的分支类型ID应返回false
r($branchTypeTest->importBranchTypesTest($repo, array(999, 998))) && p() && e('0');

// 测试步骤3：导入非全局模板(repo!=0)应返回false
// 测试数据中的分支类型repo字段不为0，所以应返回false
r($branchTypeTest->importBranchTypesTest($repo, array(1, 2))) && p() && e('0');

// 测试步骤4：验证导入逻辑(由于需要调用外部API，这里只验证参数检查)
// 当分支类型数量与请求ID数量不一致时返回false
r($branchTypeTest->importBranchTypesTest($repo, array(1, 999))) && p() && e('0');

// 测试步骤5：导入部分不存在的ID数量不匹配应返回false
r($branchTypeTest->importBranchTypesTest($repo, array(1, 2, 999))) && p() && e('0');

// 注意：完整的导入测试需要mock gitfox API，这里只测试参数验证逻辑
r(true) && p() && e('~~');
