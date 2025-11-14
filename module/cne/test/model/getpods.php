#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getPods();
timeout=0
cid=15621

- 步骤1：正常情况测试获取实例pods（实例ID=1） @0
- 步骤2：使用指定组件mysql获取pods @0
- 步骤3：测试无效实例ID的容错处理（实例ID=999） @0
- 步骤4：测试负数实例ID的边界情况（实例ID=-1） @0
- 步骤5：测试无效组件名的容错处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('instance')->loadYaml('instance_getpods', false, 2)->gen(5);
zenData('space')->loadYaml('space_getpods', false, 2)->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->getPodsTest(1)) && p() && e('0'); // 步骤1：正常情况测试获取实例pods（实例ID=1）
r($cneTest->getPodsTest(2, 'mysql')) && p() && e('0'); // 步骤2：使用指定组件mysql获取pods
r($cneTest->getPodsTest(999)) && p() && e('0'); // 步骤3：测试无效实例ID的容错处理（实例ID=999）
r($cneTest->getPodsTest(-1)) && p() && e('0'); // 步骤4：测试负数实例ID的边界情况（实例ID=-1）
r($cneTest->getPodsTest(3, 'invalid-component')) && p() && e('0'); // 步骤5：测试无效组件名的容错处理