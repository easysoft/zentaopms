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
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->loadYaml('product')->gen(30);
zenData('branch')->loadYaml('branch')->gen(30);
su('admin');

global $lang;
$branch = new branchModelTest();

r($branch->changeBranchLanguageTest(1)) && p() && e('0');       // 步骤1：正常产品类型，期望返回false
$lang->branch->create = '新建%s';
r($branch->changeBranchLanguageTest(6)) && p() && e('新建分支'); // 步骤2：多分支产品，验证语言修改
$lang->branch->create = '新建%s';
r($branch->changeBranchLanguageTest(11)) && p() && e('新建平台'); // 步骤3：多平台产品，验证语言修改
r($branch->changeBranchLanguageTest(999)) && p() && e('0');      // 步骤4：不存在的产品ID
r($branch->changeBranchLanguageTest(0)) && p() && e('0');        // 步骤5：边界值产品ID为0
r($branch->changeBranchLanguageTest(26)) && p() && e('0');       // 步骤6：再次测试正常产品类型确保一致性
