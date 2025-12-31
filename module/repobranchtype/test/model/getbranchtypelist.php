#!/usr/bin/env php
<?php

/**

title=测试 repobranchtypeModel::getBranchTypeList();
timeout=0
cid=0

- 测试步骤1：获取repoID=1的所有分支类型(应返回1条) @1
- 测试步骤2：按名称搜索"branch_type1" 统计数量 @1
- 测试步骤3：按key搜索"branch_type1" 验证key属性 @branch_type1
- 测试步骤4：按prefix搜索"dev/" 验证第一个prefix @dev/
- 测试步骤5：不传repoID获取所有分支类型(返回0表示空) @0
- 测试步骤6：验证prefix字段被正确解析为数组 @dev/
- 测试步骤7：验证返回的对象包含所有必要字段 属性name,key @branch_type1,branch_type1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 加载测试数据
zenData('ops_branch_type')->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$branchTypeTest = new repobranchtypeTest();

// 测试步骤1：获取repoID=1的所有分支类型(应返回1条)
r(count($branchTypeTest->getBranchTypeListTest(1))) && p() && e('1');

// 测试步骤2：按名称搜索"branch_type1" 统计数量
r(count($branchTypeTest->getBranchTypeListTest(1, 'branch_type1'))) && p() && e('1');

// 测试步骤3：按key搜索"branch_type1" 验证key属性
r($branchTypeTest->getBranchTypeListTest(1, '', 'branch_type1')) && p('1:key') && e('branch_type1');

// 测试步骤4：按prefix搜索"dev/" 验证第一个prefix
r($branchTypeTest->getBranchTypeListTest(1, '', '', 'dev/')) && p('1:prefix', '|') && e('dev/');

// 测试步骤5：不传repoID获取所有分支类型(返回0表示空)
r($branchTypeTest->getBranchTypeListTest(0)) && p() && e('0');

// 测试步骤6：验证prefix字段被正确解析为数组
r($branchTypeTest->getBranchTypeListTest(1)) && p('1:prefix', '|') && e('dev/');

// 测试步骤7：验证返回的对象包含所有必要字段 属性name,key
r($branchTypeTest->getBranchTypeListTest(1)) && p('1:name,key') && e('branch_type1,branch_type1');
