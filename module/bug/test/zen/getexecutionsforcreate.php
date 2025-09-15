#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getExecutionsForCreate();
timeout=0
cid=0

- 执行bugTest模块的getExecutionsForCreateTest方法，参数是$bug1 属性executionID @101
- 执行bugTest模块的getExecutionsForCreateTest方法，参数是$bug2 属性executionID @~~
- 执行bugTest模块的getConfigFields方法，参数是, 'execution, ') === false  @1
- 执行$result4->executions @0
- 执行$result5->executions) && isset($result5->execution) && isset($result5->executionID @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：测试正常情况：有效产品和项目ID
$bug1 = new stdclass();
$bug1->productID = 1;
$bug1->projectID = 11;
$bug1->executionID = 101;
$bug1->branch = '';
r($bugTest->getExecutionsForCreateTest($bug1)) && p('executionID') && e('101');

// 步骤2：测试边界情况：无效executionID的处理
$bug2 = new stdclass();
$bug2->productID = 1;
$bug2->projectID = 11;
$bug2->executionID = 999;
$bug2->branch = '';
r($bugTest->getExecutionsForCreateTest($bug2)) && p('executionID') && e('~~');

// 步骤3：测试非多执行项目的配置修改
$bug3 = new stdclass();
$bug3->productID = 1;
$bug3->projectID = 15;
$bug3->executionID = 110;
$bug3->branch = '';
$result3 = $bugTest->getExecutionsForCreateTest($bug3);
r(strpos($bugTest->getConfigFields(), 'execution,') === false) && p() && e('1');

// 步骤4：测试空产品ID的处理
$bug4 = new stdclass();
$bug4->productID = 0;
$bug4->projectID = 11;
$bug4->executionID = 101;
$bug4->branch = '';
$result4 = $bugTest->getExecutionsForCreateTest($bug4);
r(count($result4->executions)) && p() && e('0');

// 步骤5：测试更新bug对象的属性绑定
$bug5 = new stdclass();
$bug5->productID = 2;
$bug5->projectID = 12;
$bug5->executionID = 103;
$bug5->branch = '';
$result5 = $bugTest->getExecutionsForCreateTest($bug5);
r(isset($result5->executions) && isset($result5->execution) && isset($result5->executionID)) && p() && e('1');