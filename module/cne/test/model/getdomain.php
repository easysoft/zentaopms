#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getDomain();
timeout=0
cid=0

- 步骤1：空component参数获取域名 @~~
- 步骤2：默认参数获取域名 @~~
- 步骤3：指定mysql组件获取域名 @~~
- 步骤4：指定web组件获取域名 @~~
- 步骤5：无效组件名的容错性 @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 3. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->getDomainTest('')) && p() && e('~~'); // 步骤1：空component参数获取域名
r($cneTest->getDomainTest()) && p() && e('~~'); // 步骤2：默认参数获取域名
r($cneTest->getDomainTest('mysql')) && p() && e('~~'); // 步骤3：指定mysql组件获取域名
r($cneTest->getDomainTest('web')) && p() && e('~~'); // 步骤4：指定web组件获取域名
r($cneTest->getDomainTest('invalid-component-name')) && p() && e('~~'); // 步骤5：无效组件名的容错性