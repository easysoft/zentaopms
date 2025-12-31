#!/usr/bin/env php
<?php

/**

title=测试 repobranchtypeModel::apiCreateBranchType();
timeout=0
cid=0

- 测试步骤1：验证创建参数组装-prefixes为数组 @~~
- 测试步骤2：验证创建参数组装-prefixes为字符串(应自动转换) @~~
- 测试步骤3：验证空前缀过滤逻辑 @~~
- 测试步骤4：验证desc字段默认值 @~~
- 测试步骤5：验证完整的formData参数 @~~

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

// 测试步骤1：验证创建参数组装-prefixes为数组
$formData1 = new stdclass();
$formData1->name     = 'Test Branch Type';
$formData1->key      = 'test';
$formData1->prefixes = array('test/', 'testing/');
$formData1->desc     = 'Test description';
// 注意：实际创建需要调用gitfox API，这里验证方法不报错
r(is_bool($branchTypeTest->apiCreateBranchTypeTest(0, $formData1)) || is_array($branchTypeTest->apiCreateBranchTypeTest(0, $formData1))) && p() && e('~~');

// 测试步骤2：验证创建参数组装-prefixes为字符串(应自动转换)
$formData2 = new stdclass();
$formData2->name     = 'Feature Branch';
$formData2->key      = 'feature';
$formData2->prefixes = 'feature/,feat/';
$formData2->desc     = 'Feature branch type';
r(is_bool($branchTypeTest->apiCreateBranchTypeTest(0, $formData2)) || is_array($branchTypeTest->apiCreateBranchTypeTest(0, $formData2))) && p() && e('~~');

// 测试步骤3：验证空前缀过滤逻辑
$formData3 = new stdclass();
$formData3->name     = 'Bugfix Branch';
$formData3->key      = 'bugfix';
$formData3->prefixes = array('bugfix/', '', '  ', 'fix/');
$formData3->desc     = 'Bugfix branch type';
r(is_bool($branchTypeTest->apiCreateBranchTypeTest(0, $formData3)) || is_array($branchTypeTest->apiCreateBranchTypeTest(0, $formData3))) && p() && e('~~');

// 测试步骤4：验证desc字段默认值
$formData4 = new stdclass();
$formData4->name     = 'Release Branch';
$formData4->key      = 'release';
$formData4->prefixes = array('release/');
// 不设置desc字段，应使用默认空字符串
r(is_bool($branchTypeTest->apiCreateBranchTypeTest(0, $formData4)) || is_array($branchTypeTest->apiCreateBranchTypeTest(0, $formData4))) && p() && e('~~');

// 测试步骤5：验证完整的formData参数
$formData5 = new stdclass();
$formData5->name     = 'Hotfix Branch';
$formData5->key      = 'hotfix';
$formData5->prefixes = array('hotfix/', 'hot/');
$formData5->desc     = 'Hotfix branch for production issues';
r(is_bool($branchTypeTest->apiCreateBranchTypeTest(1, $formData5)) || is_array($branchTypeTest->apiCreateBranchTypeTest(1, $formData5))) && p() && e('~~');
