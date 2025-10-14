#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::bugCreate();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性title @Bug创建表
 - 属性pivotName @Bug创建表
 - 属性currentMenu @bugcreate
- 步骤2：指定时间范围
 - 属性begin @2024-01-01
 - 属性end @2024-02-28
- 步骤3：指定产品ID
 - 属性product @1
 - 属性hasProducts @1
- 步骤4：指定执行ID
 - 属性execution @1
 - 属性hasExecutions @1
- 步骤5：完整参数验证
 - 属性hasUsers @1
 - 属性hasBugs @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（简化配置）
// 不生成复杂的测试数据，依赖现有数据库数据或模拟

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->bugCreateTest()) && p('title,pivotName,currentMenu') && e('Bug创建表,Bug创建表,bugcreate'); // 步骤1：正常情况
r($pivotTest->bugCreateTest('2024-01-01', '2024-02-28')) && p('begin,end') && e('2024-01-01,2024-02-28'); // 步骤2：指定时间范围
r($pivotTest->bugCreateTest('', '', 1)) && p('product,hasProducts') && e('1,1'); // 步骤3：指定产品ID
r($pivotTest->bugCreateTest('', '', 0, 1)) && p('execution,hasExecutions') && e('1,1'); // 步骤4：指定执行ID
r($pivotTest->bugCreateTest('2024-01-01', '2024-12-31', 2, 2)) && p('hasUsers,hasBugs') && e('1,1'); // 步骤5：完整参数验证