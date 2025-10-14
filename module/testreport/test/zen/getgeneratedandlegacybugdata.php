#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::getGeneratedAndLegacyBugData();
timeout=0
cid=0

- 步骤1：正常情况验证按用例统计数量属性4 @2
- 步骤2：空列表边界值测试属性4 @0
- 步骤3：无效时间范围测试属性4 @1
- 步骤4：空构建ID列表测试属性4 @2
- 步骤5：完整参数综合验证属性4 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('bug');
zenData('testtask');

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testreportTest = new testreportTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($testreportTest->getGeneratedAndLegacyBugDataTest(array(1, 2), array(1), '2024-01-01', '2024-01-31', array(1, 2))) && p('4') && e('2'); // 步骤1：正常情况验证按用例统计数量
r($testreportTest->getGeneratedAndLegacyBugDataTest(array(), array(), '2024-01-01', '2024-01-31', array(1, 2))) && p('4') && e('0'); // 步骤2：空列表边界值测试
r($testreportTest->getGeneratedAndLegacyBugDataTest(array(1), array(1), '', '', array(1))) && p('4') && e('1'); // 步骤3：无效时间范围测试
r($testreportTest->getGeneratedAndLegacyBugDataTest(array(1, 2), array(1), '2024-01-01', '2024-01-31', array())) && p('4') && e('2'); // 步骤4：空构建ID列表测试
r($testreportTest->getGeneratedAndLegacyBugDataTest(array(1, 2, 3), array(1, 2), '2024-01-01', '2024-01-31', array(1, 2, 3))) && p('4') && e('3'); // 步骤5：完整参数综合验证