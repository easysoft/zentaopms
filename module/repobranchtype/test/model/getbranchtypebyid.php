#!/usr/bin/env php
<?php

/**

title=测试 repobranchtypeModel::getBranchTypeByID();
timeout=0
cid=0

- 测试步骤1：正常获取存在的分支类型对象
 - 属性id @1
 - 属性name @branch_type1
 - 属性key @branch_type1
- 测试步骤2：验证prefix字段被正确解析 @dev/
- 测试步骤3：验证prefix内容 @test/
- 测试步骤4：测试不存在的typeID @0
- 测试步骤5：测试无效的typeID(0) @0
- 测试步骤6：验证第二个分支类型信息
 - 属性name @branch_type2
 - 属性key @branch_type2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 加载测试数据
zenData('ops_branch_type')->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$branchTypeTest = new repobranchtypeTest();

// 测试步骤1：正常获取存在的分支类型对象
r($branchTypeTest->getBranchTypeByIDTest(1)) && p('id,name,key') && e('1,branch_type1,branch_type1');

// 测试步骤2：验证prefix字段被正确解析为数组
r($branchTypeTest->getBranchTypeByIDTest(1)) && p('prefix', '|') && e('dev/');

// 测试步骤3：验证第二个分支类型的prefix内容
r($branchTypeTest->getBranchTypeByIDTest(2)) && p('prefix', '|') && e('test/');

// 测试步骤4：测试不存在的typeID
r($branchTypeTest->getBranchTypeByIDTest(999)) && p() && e('0');

// 测试步骤5：测试无效的typeID(0)
r($branchTypeTest->getBranchTypeByIDTest(0)) && p() && e('0');

// 测试步骤6：验证第二个分支类型信息
r($branchTypeTest->getBranchTypeByIDTest(2)) && p('name,key') && e('branch_type2,branch_type2');
