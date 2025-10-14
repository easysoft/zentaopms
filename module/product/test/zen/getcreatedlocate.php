#!/usr/bin/env php
<?php

/**

title=测试 productZen::getCreatedLocate();
timeout=0
cid=0

- 步骤1：产品tab下非模态框跳转检查结果属性result @success
- 步骤2：项目集tab下非模态框跳转检查结果属性result @success
- 步骤3：文档tab下非模态框跳转检查结果属性result @success
- 步骤4：模态框中跳转检查load值
 - 属性result @success
 - 属性load @1
- 步骤5：边界值测试检查closeModal
 - 属性result @success
 - 属性closeModal @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

$table = zenData('product');
$table->id->range('1-100');
$table->program->range('0,1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->code->range('product1,product2,product3,product4,product5');
$table->type->range('normal');
$table->status->range('normal');
$table->gen(5);

$programTable = zenData('project');
$programTable->id->range('1-5');
$programTable->name->range('项目集1,项目集2,项目集3,项目集4,项目集5');
$programTable->type->range('program');
$programTable->grade->range('1');
$programTable->status->range('doing');
$programTable->gen(5);

su('admin');

$productTest = new productTest();

r($productTest->getCreatedLocateTest(1, 0, 'product', false)) && p('result') && e('success'); // 步骤1：产品tab下非模态框跳转检查结果
r($productTest->getCreatedLocateTest(2, 1, 'program', false)) && p('result') && e('success'); // 步骤2：项目集tab下非模态框跳转检查结果
r($productTest->getCreatedLocateTest(3, 0, 'doc', false)) && p('result') && e('success'); // 步骤3：文档tab下非模态框跳转检查结果
r($productTest->getCreatedLocateTest(4, 2, 'product', true)) && p('result,load') && e('success,1'); // 步骤4：模态框中跳转检查load值
r($productTest->getCreatedLocateTest(0, 0, 'product', false)) && p('result,closeModal') && e('success,1'); // 步骤5：边界值测试检查closeModal