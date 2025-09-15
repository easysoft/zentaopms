#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getBugGroup();
timeout=0
cid=0

- 步骤1：正常时间范围查询第admin条的0:openedBy属性 @admin
- 步骤2：指定产品ID查询第admin条的0:openedBy属性 @admin
- 步骤3：指定执行ID查询第admin条的0:openedBy属性 @admin
- 步骤4：同时指定产品和执行ID第admin条的0:openedBy属性 @admin
- 步骤5：时间范围外无数据 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('bug')->loadYaml('bug')->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($pivotTest->getBugGroupTest('2025-08-01', '2025-08-31', 0, 0)) && p('admin:0:openedBy') && e('admin'); // 步骤1：正常时间范围查询
r($pivotTest->getBugGroupTest('2025-08-01', '2025-08-31', 1, 0)) && p('admin:0:openedBy') && e('admin'); // 步骤2：指定产品ID查询
r($pivotTest->getBugGroupTest('2025-08-01', '2025-08-31', 0, 101)) && p('admin:0:openedBy') && e('admin'); // 步骤3：指定执行ID查询
r($pivotTest->getBugGroupTest('2025-08-01', '2025-08-31', 1, 101)) && p('admin:0:openedBy') && e('admin'); // 步骤4：同时指定产品和执行ID
r($pivotTest->getBugGroupTest('2024-01-01', '2024-01-31', 0, 0)) && p() && e('0'); // 步骤5：时间范围外无数据