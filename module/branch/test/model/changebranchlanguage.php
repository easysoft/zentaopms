#!/usr/bin/env php
<?php

/**

title=测试 branchModel::changeBranchLanguage();
timeout=0
cid=15320

- 步骤1：正常产品类型，期望返回false @0
- 步骤2：多分支产品，验证语言修改 @新建分支
- 步骤3：多平台产品，验证语言修改 @新建平台
- 步骤4：不存在的产品ID @0
- 步骤5：边界值产品ID为0 @0
- 步骤6：再次测试正常产品类型确保一致性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

$product = zenData('product');
$product->id->range('41-46');
$product->type->range('normal,branch,platform,normal');
$product->gen(6);

zenData('branch')->gen(10);
su('admin');

$branch = new branchTest();

r($branch->changeBranchLanguageTest(41)) && p() && e('0');           // 步骤1：正常产品类型，期望返回false
r($branch->changeBranchLanguageTest(42)) && p() && e('新建分支');     // 步骤2：多分支产品，验证语言修改
r($branch->changeBranchLanguageTest(43)) && p() && e('新建平台');     // 步骤3：多平台产品，验证语言修改
r($branch->changeBranchLanguageTest(999)) && p() && e('0');          // 步骤4：不存在的产品ID
r($branch->changeBranchLanguageTest(0)) && p() && e('0');            // 步骤5：边界值产品ID为0
r($branch->changeBranchLanguageTest(44)) && p() && e('0');           // 步骤6：再次测试正常产品类型确保一致性