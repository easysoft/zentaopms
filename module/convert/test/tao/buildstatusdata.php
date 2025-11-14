#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildStatusData();
timeout=0
cid=15826

- 步骤1：正常情况-检查id属性id @1
- 步骤2：包含额外字段-检查sequence属性sequence @200
- 步骤3：缺少description字段-检查pname属性pname @Closed
- 步骤4：空值字段-检查id属性id @0
- 步骤5：特殊字符和数字-检查pname属性pname @Status with 特殊字符 & symbols!

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($convertTest->buildStatusDataTest(array('id' => 1, 'sequence' => 100, 'name' => 'Open', 'description' => 'Issue is open'))) && p('id') && e('1'); // 步骤1：正常情况-检查id
r($convertTest->buildStatusDataTest(array('id' => 2, 'sequence' => 200, 'name' => 'In Progress', 'description' => 'Issue in progress', 'extra' => 'extra_data'))) && p('sequence') && e('200'); // 步骤2：包含额外字段-检查sequence
r($convertTest->buildStatusDataTest(array('id' => 3, 'sequence' => 300, 'name' => 'Closed'))) && p('pname') && e('Closed'); // 步骤3：缺少description字段-检查pname
r($convertTest->buildStatusDataTest(array('id' => 0, 'sequence' => 0, 'name' => '', 'description' => ''))) && p('id') && e('0'); // 步骤4：空值字段-检查id
r($convertTest->buildStatusDataTest(array('id' => '999', 'sequence' => -1, 'name' => 'Status with 特殊字符 & symbols!', 'description' => 'Description with 中文 & HTML <tags>'))) && p('pname') && e('Status with 特殊字符 & symbols!'); // 步骤5：特殊字符和数字-检查pname