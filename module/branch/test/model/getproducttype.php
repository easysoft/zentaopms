#!/usr/bin/env php
<?php

/**

title=测试 branchModel::getProductType();
timeout=0
cid=15330

- 测试步骤1：测试获取branch类型产品的分支ID @branch
- 测试步骤2：测试获取platform类型产品的分支ID @platform
- 测试步骤3：测试获取normal类型产品的分支ID @normal
- 测试步骤4：测试不存在的分支ID @0
- 测试步骤5：测试边界值0分支ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->loadYaml('product')->gen(30);
zenData('branch')->loadYaml('branch')->gen(30);
su('admin');

$branchTest = new branchModelTest();

r($branchTest->getProductTypeTest(1)) && p() && e('branch');     // 测试步骤1：测试获取branch类型产品的分支ID
r($branchTest->getProductTypeTest(17)) && p() && e('platform');  // 测试步骤2：测试获取platform类型产品的分支ID
r($branchTest->getProductTypeTest(26)) && p() && e('normal');    // 测试步骤3：测试获取normal类型产品的分支ID
r($branchTest->getProductTypeTest(999)) && p() && e('0');        // 测试步骤4：测试不存在的分支ID
r($branchTest->getProductTypeTest(0)) && p() && e('0');          // 测试步骤5：测试边界值0分支ID