#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getImportedData();
timeout=0
cid=0

- 步骤1：空文件路径第0条的caseData属性 @rray()
- 步骤2：无效产品ID第0条的caseData属性 @rray()
- 步骤3：负数产品ID第0条的caseData属性 @rray()
- 步骤4：不存在产品ID第0条的caseData属性 @rray()
- 步骤5：不存在文件第0条的caseData属性 @rray()

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('禅道项目管理,测试产品{3},演示产品');
$product->code->range('zentao,test{3},demo');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(5);

$case = zenData('case');
zendata('case')->loadYaml('case_getimporteddata', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->getImportedDataTest(1, '')) && p('0:caseData') && e(array()); // 步骤1：空文件路径
r($testcaseTest->getImportedDataTest(0, '')) && p('0:caseData') && e(array()); // 步骤2：无效产品ID
r($testcaseTest->getImportedDataTest(-1, '')) && p('0:caseData') && e(array()); // 步骤3：负数产品ID
r($testcaseTest->getImportedDataTest(999, '')) && p('0:caseData') && e(array()); // 步骤4：不存在产品ID
r($testcaseTest->getImportedDataTest(1, 'nonexistent.csv')) && p('0:caseData') && e(array()); // 步骤5：不存在文件