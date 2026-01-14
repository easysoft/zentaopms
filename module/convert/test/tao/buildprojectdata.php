#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildProjectData();
timeout=0
cid=15823

- 步骤1：测试id字段属性id @1
- 步骤2：测试pname字段属性pname @Project Name
- 步骤3：测试pkey字段属性pkey @TESTKEY
- 步骤4：测试pstatus字段属性pstatus @inactive
- 步骤5：测试description字段属性description @Test Description

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->buildProjectDataTest(array('id' => 1, 'name' => 'Test Project', 'key' => 'TEST', 'status' => 'active'))) && p('id') && e('1'); // 步骤1：测试id字段

r($convertTest->buildProjectDataTest(array('id' => 2, 'name' => 'Project Name'))) && p('pname') && e('Project Name'); // 步骤2：测试pname字段

r($convertTest->buildProjectDataTest(array('id' => 3, 'key' => 'TESTKEY'))) && p('pkey') && e('TESTKEY'); // 步骤3：测试pkey字段

r($convertTest->buildProjectDataTest(array('id' => 4, 'status' => 'inactive'))) && p('pstatus') && e('inactive'); // 步骤4：测试pstatus字段

r($convertTest->buildProjectDataTest(array('id' => 5, 'description' => 'Test Description'))) && p('description') && e('Test Description'); // 步骤5：测试description字段