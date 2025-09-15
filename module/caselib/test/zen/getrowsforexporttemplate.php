#!/usr/bin/env php
<?php

/**

title=- 测试模块名称包含ID格式化 >> 模块1(
timeout=0
cid=101

- 执行caselibTest模块的getRowsForExportTemplateTest方法，参数是2, array  @6
- 执行caselibTest模块的getRowsForExportTemplateTest方法，参数是0, array  @0
- 执行caselibTest模块的getRowsForExportTemplateTest方法，参数是1, array  @1
- 执行caselibTest模块的getRowsForExportTemplateTest方法，参数是1, array  @模块1(#101)
- 执行caselibTest模块的getRowsForExportTemplateTest方法，参数是1, array  @1. \n2. \n3.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

$table = zenData('module');
$table->id->range('101-110');
$table->name->range('模块1,模块2,模块3,测试模块{4}');
$table->type->range('caselib');
$table->root->range('1{10}');
$table->deleted->range('0');
$table->gen(10);

su('admin');
$caselibTest = new caselibTest();

r($caselibTest->getRowsForExportTemplateTest(2, array(101 => '模块1', 102 => '模块2', 103 => '模块3'), 'count')) && p() && e(6);
r($caselibTest->getRowsForExportTemplateTest(0, array(101 => '模块1'), 'count')) && p() && e(0);
r($caselibTest->getRowsForExportTemplateTest(1, array(101 => '模块1'), 'count')) && p() && e(1);
r($caselibTest->getRowsForExportTemplateTest(1, array(101 => '模块1'), 'first_module')) && p() && e('模块1(#101)');
r($caselibTest->getRowsForExportTemplateTest(1, array(101 => '模块1'), 'first_stepDesc')) && p() && e("1. \n2. \n3.");