#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getAllProductsIDAndName();
timeout=0
cid=0

- 步骤1：正常情况获取所有产品ID和名称 @1
- 步骤2：验证第1个产品名称属性1 @产品A
- 步骤3：验证第2个产品名称属性2 @产品B
- 步骤4：验证第3个产品名称属性3 @产品C
- 步骤5：验证未删除产品数量为8个 @8

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品A,产品B,产品C,产品D,产品E,产品F,产品G,产品H,产品I,产品J');
$table->code->range('PRODA,PRODB,PRODC,PRODD,PRODE,PRODF,PRODG,PRODH,PRODI,PRODJ');
$table->status->range('normal{8},closed{2}');
$table->deleted->range('0{8},1{2}');
$table->shadow->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(is_array($pivotTest->getAllProductsIDAndNameTest())) && p() && e('1'); // 步骤1：正常情况获取所有产品ID和名称
r($pivotTest->getAllProductsIDAndNameTest()) && p('1') && e('产品A'); // 步骤2：验证第1个产品名称
r($pivotTest->getAllProductsIDAndNameTest()) && p('2') && e('产品B'); // 步骤3：验证第2个产品名称  
r($pivotTest->getAllProductsIDAndNameTest()) && p('3') && e('产品C'); // 步骤4：验证第3个产品名称
r(count($pivotTest->getAllProductsIDAndNameTest())) && p() && e('8'); // 步骤5：验证未删除产品数量为8个