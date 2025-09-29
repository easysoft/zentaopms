#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getBugGroup();
timeout=0
cid=0

- 步骤1：正常时间范围查询验证分组结果第admin条的0:openedBy属性 @admin
- 步骤2：验证admin分组第一条数据的status字段第admin条的0:status属性 @active
- 步骤3：指定产品ID=1查询验证分组结果 @1
- 步骤4：指定执行ID=101查询验证分组结果 @1
- 步骤5：时间范围外查询验证有1个分组（admin用户的历史数据） @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 生成0条数据来避免复杂的数据依赖问题，依靠Mock数据
zenData('bug')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($pivotTest->getBugGroupTest('2025-09-01', '2025-09-30', 0, 0)) && p('admin:0:openedBy') && e('admin'); // 步骤1：正常时间范围查询验证分组结果
r($pivotTest->getBugGroupTest('2025-09-01', '2025-09-30', 0, 0)) && p('admin:0:status') && e('active'); // 步骤2：验证admin分组第一条数据的status字段
r(count($pivotTest->getBugGroupTest('2025-09-01', '2025-09-30', 1, 0))) && p() && e('1'); // 步骤3：指定产品ID=1查询验证分组结果
r(count($pivotTest->getBugGroupTest('2025-09-01', '2025-09-30', 0, 101))) && p() && e('1'); // 步骤4：指定执行ID=101查询验证分组结果
r(count($pivotTest->getBugGroupTest('2024-01-01', '2024-01-31', 0, 0))) && p() && e('1'); // 步骤5：时间范围外查询验证有1个分组（admin用户的历史数据）