#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoStatus();
timeout=0
cid=15791

- 步骤1：测试task模块，task有7个状态（包括空字符串） @7
- 步骤2：测试testcase模块，包含额外add_case_status状态 @6
- 步骤3：测试story模块，有6个状态 @6
- 步骤4：测试bug模块，有4个状态 @4
- 步骤5：验证testcase模块特殊处理属性add_case_status @新增

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(count($convertTest->getZentaoStatusTest('task'))) && p() && e('7'); // 步骤1：测试task模块，task有7个状态（包括空字符串）
r(count($convertTest->getZentaoStatusTest('testcase'))) && p() && e('6'); // 步骤2：测试testcase模块，包含额外add_case_status状态
r(count($convertTest->getZentaoStatusTest('story'))) && p() && e('6'); // 步骤3：测试story模块，有6个状态
r(count($convertTest->getZentaoStatusTest('bug'))) && p() && e('4'); // 步骤4：测试bug模块，有4个状态
r($convertTest->getZentaoStatusTest('testcase')) && p('add_case_status') && e('新增'); // 步骤5：验证testcase模块特殊处理