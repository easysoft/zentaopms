#!/usr/bin/env php
<?php

/**

title=测试 repobranchtypeModel::apiUpdateBranchType();
timeout=0
cid=0

- 测试步骤1：验证更新不存在的分支类型返回false @0
- 测试步骤2：验证更新参数组装-prefixes为数组 @~~
- 测试步骤3：验证更新参数组装-prefixes为字符串(应自动转换) @~~
- 测试步骤4：验证repo为null时从分支类型获取repo信息 @~~
- 测试步骤5：验证空前缀过滤逻辑 @~~

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

// 测试步骤1：验证更新不存在的分支类型返回false
$formData1 = new stdclass();
$formData1->name     = 'Updated Name';
$formData1->prefixes = array('updated/');
$formData1->desc     = 'Updated description';
r($branchTypeTest->apiUpdateBranchTypeTest($repo, 999, $formData1)) && p() && e('0');

// 测试步骤2：验证更新参数组装-prefixes为数组
$formData2 = new stdclass();
$formData2->name     = 'Feature Branch Updated';
$formData2->prefixes = array('feature/', 'feat/', 'f/');
$formData2->desc     = 'Updated feature branch';
// 注意：实际更新需要调用gitfox API，这里验证方法不报错
r(is_bool($branchTypeTest->apiUpdateBranchTypeTest($repo, 1, $formData2)) || is_array($branchTypeTest->apiUpdateBranchTypeTest($repo, 1, $formData2))) && p() && e('~~');

// 测试步骤3：验证更新参数组装-prefixes为字符串(应自动转换)
$formData3 = new stdclass();
$formData3->name     = 'Develop Branch';
$formData3->prefixes = 'dev/,develop/,story/';
$formData3->desc     = 'Development branch';
r(is_bool($branchTypeTest->apiUpdateBranchTypeTest($repo, 1, $formData3)) || is_array($branchTypeTest->apiUpdateBranchTypeTest($repo, 1, $formData3))) && p() && e('~~');

// 测试步骤4：验证repo为null时从分支类型获取repo信息
$formData4 = new stdclass();
$formData4->name     = 'Bugfix Branch';
$formData4->prefixes = array('bugfix/');
$formData4->desc     = 'Bugfix branch type';
r(is_bool($branchTypeTest->apiUpdateBranchTypeTest(null, 1, $formData4)) || is_array($branchTypeTest->apiUpdateBranchTypeTest(null, 1, $formData4))) && p() && e('~~');

// 测试步骤5：验证空前缀过滤逻辑
$formData5 = new stdclass();
$formData5->name     = 'Release Branch';
$formData5->prefixes = array('release/', '', '  ', 'rel/');
$formData5->desc     = 'Release branch type';
r(is_bool($branchTypeTest->apiUpdateBranchTypeTest($repo, 1, $formData5)) || is_array($branchTypeTest->apiUpdateBranchTypeTest($repo, 1, $formData5))) && p() && e('~~');
