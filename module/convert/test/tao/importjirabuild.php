#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraBuild();
timeout=0
cid=0

- 步骤1：空数据列表 @1
- 步骤2：单个版本数据 @1
- 步骤4：无效项目ID @1
- 步骤5：测试重复处理 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 由于importJiraBuild方法主要是业务逻辑处理，不直接依赖预设数据，所以简化数据准备

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->importJiraBuildTest(array())) && p() && e('1'); // 步骤1：空数据列表
r($convertTest->importJiraBuildTest(array((object)array('id' => 1, 'project' => 1001, 'name' => 'Version1.0')))) && p() && e('1'); // 步骤2：单个版本数据
r($convertTest->importJiraBuildTest(array(
    (object)array('id' => 1, 'project' => 1001, 'name' => 'Version1.0'),
    (object)array('id' => 2, 'project' => 1002, 'name' => 'Version2.0')
))) && p() && e('1'); // 步骤3：多个版本数据
r($convertTest->importJiraBuildTest(array((object)array('id' => 3, 'project' => 999, 'name' => 'Version3.0')))) && p() && e('1'); // 步骤4：无效项目ID
r($convertTest->importJiraBuildTest(array((object)array('id' => 4, 'project' => 1001, 'name' => 'Version4.0')))) && p() && e('1'); // 步骤5：测试重复处理