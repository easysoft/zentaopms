#!/usr/bin/env php
<?php

/**

title=测试 groupModel::checkNavSubset();
timeout=0
cid=16695

- 步骤1：空nav参数测试，期望返回true @1
- 步骤2：general nav且subset无配置，期望返回true @1
- 步骤3：general nav但subset配置为my，期望返回false @0
- 步骤4：nav与subset配置匹配，期望返回true @1
- 步骤5：nav与subset配置不匹配，期望返回false @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/group.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$groupTest = new groupTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($groupTest->checkNavSubsetTest('', 'my')) && p() && e('1'); // 步骤1：空nav参数测试，期望返回true
r($groupTest->checkNavSubsetTest('general', 'noconfig')) && p() && e('1'); // 步骤2：general nav且subset无配置，期望返回true  
r($groupTest->checkNavSubsetTest('general', 'my')) && p() && e('0'); // 步骤3：general nav但subset配置为my，期望返回false
r($groupTest->checkNavSubsetTest('my', 'my')) && p() && e('1'); // 步骤4：nav与subset配置匹配，期望返回true
r($groupTest->checkNavSubsetTest('product', 'my')) && p() && e('0'); // 步骤5：nav与subset配置不匹配，期望返回false