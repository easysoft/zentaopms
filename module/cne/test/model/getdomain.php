#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getDomain();
timeout=0
cid=0

- 步骤1：正常情况获取域名（API连接失败返回null） @0
- 步骤2：使用默认空参数（API连接失败返回null） @0
- 步骤3：使用mysql组件名（API连接失败返回null） @0
- 步骤4：使用web组件名（API连接失败返回null） @0
- 步骤5：使用无效组件名验证容错性（API连接失败返回null） @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('instance')->loadYaml('instance', false, 2)->gen(2);
zendata('space')->loadYaml('space', false, 1)->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($cneTest->getDomainTest('')) && p() && e('0'); // 步骤1：正常情况获取域名（API连接失败返回null）
r($cneTest->getDomainTest()) && p() && e('0'); // 步骤2：使用默认空参数（API连接失败返回null）
r($cneTest->getDomainTest('mysql')) && p() && e('0'); // 步骤3：使用mysql组件名（API连接失败返回null）
r($cneTest->getDomainTest('web')) && p() && e('0'); // 步骤4：使用web组件名（API连接失败返回null）
r($cneTest->getDomainTest('invalid-component')) && p() && e('0'); // 步骤5：使用无效组件名验证容错性（API连接失败返回null）