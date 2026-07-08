#!/usr/bin/env php
<?php

/**

title=测试 repobranchtypeModel::getBranchTypeByIDs();
timeout=0
cid=0

- 测试步骤1：批量获取存在的分支类型对象(ID 1和2)
 - 属性1.id @1
 - 属性2.id @2
- 测试步骤2：验证返回结果数量为2 @2
- 测试步骤3：验证第一个分支类型的prefix字段被正确解析 @dev/
- 测试步骤4：验证第二个分支类型的prefix字段被正确解析 @test/
- 测试步骤5：测试空数组参数 @0
- 测试步骤6：测试包含不存在ID的数组(ID 999) @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 加载测试数据
zenData('ops_branch_type')->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$branchTypeTest = new repobranchtypeTest();

// 测试步骤1：批量获取存在的分支类型对象(ID 1和2)
r($branchTypeTest->getBranchTypeByIDsTest(array(1, 2))) && p('1:id;2:id') && e('1,2');

// 测试步骤2：验证返回结果数量为2
r(count($branchTypeTest->getBranchTypeByIDsTest(array(1, 2)))) && p() && e('2');

// 测试步骤3：验证第一个分支类型的prefix字段被正确解析
r($branchTypeTest->getBranchTypeByIDsTest(array(1))) && p('1:prefix', '|') && e('dev/');

// 测试步骤4：验证第二个分支类型的prefix字段被正确解析
r($branchTypeTest->getBranchTypeByIDsTest(array(2))) && p('2:prefix', '|') && e('test/');

// 测试步骤5：测试空数组参数
r(count($branchTypeTest->getBranchTypeByIDsTest(array()))) && p() && e('0');

// 测试步骤6：测试包含不存在ID的数组(ID 999)
r(count($branchTypeTest->getBranchTypeByIDsTest(array(999)))) && p() && e('0');
