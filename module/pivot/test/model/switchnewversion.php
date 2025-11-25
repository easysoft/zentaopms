#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::switchNewVersion();
timeout=0
cid=17435

- 步骤1:正常更新版本号,有效的ID和版本号 @1
- 步骤2:更新为不同版本号格式 @1
- 步骤3:更新已存在的ID @1
- 步骤4:更新为数字版本号 @1
- 步骤5:更新为较长的版本号 @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备
zendata('pivot')->loadYaml('switchnewversion', false, 2)->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$pivotTest = new pivotTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($pivotTest->switchNewVersionTest(1, '2')) && p() && e('1'); // 步骤1:正常更新版本号,有效的ID和版本号
r($pivotTest->switchNewVersionTest(2, '1.0.1')) && p() && e('1'); // 步骤2:更新为不同版本号格式
r($pivotTest->switchNewVersionTest(3, '2.1')) && p() && e('1'); // 步骤3:更新已存在的ID
r($pivotTest->switchNewVersionTest(4, '10')) && p() && e('1'); // 步骤4:更新为数字版本号
r($pivotTest->switchNewVersionTest(5, '20.1.1.5')) && p() && e('1'); // 步骤5:更新为较长的版本号