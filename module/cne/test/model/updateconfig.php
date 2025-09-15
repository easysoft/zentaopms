#!/usr/bin/env php
<?php

/**

title=测试 cneModel::updateConfig();
timeout=0
cid=0

- 步骤1：正常配置更新（API错误返回false） @0
- 步骤2：带版本参数更新（API错误返回false） @0
- 步骤3：带强制重启参数更新（API错误返回false） @0
- 步骤4：带设置片段更新（API错误返回false） @0
- 步骤5：带设置映射更新（API错误返回false） @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('instance')->loadYaml('instance', false, 2)->gen(3);
zendata('space')->loadYaml('space', false, 1)->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->updateConfigTest()) && p() && e('0'); // 步骤1：正常配置更新（API错误返回false）
r($cneTest->updateConfigTest('2024.04.2401')) && p() && e('0'); // 步骤2：带版本参数更新（API错误返回false）
r($cneTest->updateConfigTest(null, true)) && p() && e('0'); // 步骤3：带强制重启参数更新（API错误返回false）
r($cneTest->updateConfigTest(null, null, array('key1' => 'value1'))) && p() && e('0'); // 步骤4：带设置片段更新（API错误返回false）
r($cneTest->updateConfigTest(null, false, null, (object)array('setting1' => 'map1'))) && p() && e('0'); // 步骤5：带设置映射更新（API错误返回false）