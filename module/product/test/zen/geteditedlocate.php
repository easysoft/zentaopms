#!/usr/bin/env php
<?php

/**

title=测试 productZen::getEditedLocate();
timeout=0
cid=0

- 步骤1：正常情况-有产品ID和项目集ID
 - 属性result @success
 - 属性message @保存成功
- 步骤2：边界值-只有产品ID没有项目集ID
 - 属性result @success
 - 属性message @保存成功
- 步骤3：边界值-产品ID为0但有项目集ID
 - 属性result @success
 - 属性message @保存成功
- 步骤4：边界值-产品ID和项目集ID都为0
 - 属性result @success
 - 属性message @保存成功
- 步骤5：业务规则-验证无项目集时的session设置
 - 属性result @success
 - 属性message @保存成功

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->code->range('product1,product2,product3,product4,product5');
$table->program->range('0,1,2,0,1');
$table->status->range('normal{5}');
$table->PO->range('admin');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->getEditedLocateTest(1, 1)) && p('result,message') && e('success,保存成功'); // 步骤1：正常情况-有产品ID和项目集ID
r($productTest->getEditedLocateTest(1, 0)) && p('result,message') && e('success,保存成功'); // 步骤2：边界值-只有产品ID没有项目集ID
r($productTest->getEditedLocateTest(0, 1)) && p('result,message') && e('success,保存成功'); // 步骤3：边界值-产品ID为0但有项目集ID
r($productTest->getEditedLocateTest(0, 0)) && p('result,message') && e('success,保存成功'); // 步骤4：边界值-产品ID和项目集ID都为0
r($productTest->getEditedLocateTest(5, 0)) && p('result,message') && e('success,保存成功'); // 步骤5：业务规则-验证无项目集时的session设置