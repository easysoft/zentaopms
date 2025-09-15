#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildProductForClose();
timeout=0
cid=0

- 步骤1：有效产品ID关闭属性status @close
- 步骤2：不存在产品ID关闭属性status @close
- 步骤3：产品ID为0关闭属性status @close
- 步骤4：产品ID为负数关闭属性status @close
- 步骤5：验证关闭日期为当前日期属性closedDate @2025-09-15

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->loadYaml('product_buildproductforclose', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->buildProductForCloseTest(1)) && p('status') && e('close'); // 步骤1：有效产品ID关闭
r($productTest->buildProductForCloseTest(999)) && p('status') && e('close'); // 步骤2：不存在产品ID关闭
r($productTest->buildProductForCloseTest(0)) && p('status') && e('close'); // 步骤3：产品ID为0关闭
r($productTest->buildProductForCloseTest(-1)) && p('status') && e('close'); // 步骤4：产品ID为负数关闭
r($productTest->buildProductForCloseTest(5)) && p('closedDate') && e('2025-09-15'); // 步骤5：验证关闭日期为当前日期