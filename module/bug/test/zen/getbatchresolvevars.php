#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getBatchResolveVars();
timeout=0
cid=15447

- 执行bugTest模块的getBatchResolveVarsTest方法，参数是array 属性1 @tester1
- 执行bugTest模块的getBatchResolveVarsTest方法，参数是array 属性1 @tester1
- 执行bugTest模块的getBatchResolveVarsTest方法，参数是array 属性1 @tester2
- 执行bugTest模块的getBatchResolveVarsTest方法，参数是array 属性1 @tester3
- 执行bugTest模块的getBatchResolveVarsTest方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zendata('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->code->range('product1,product2,product3');
$product->type->range('normal');
$product->status->range('normal');
$product->QD->range('tester1,tester2,tester3');
$product->deleted->range('0');
$product->gen(3);

$bug = zendata('bug');
$bug->id->range('1-10');
$bug->product->range('1{5},2{3},3{2}');
$bug->module->range('1,2,3,4,5,1,2,3,1,2');
$bug->title->range('Bug1,Bug2,Bug3,Bug4,Bug5,Bug6,Bug7,Bug8,Bug9,Bug10');
$bug->status->range('active');
$bug->deleted->range('0');
$bug->gen(10);

$module = zendata('module');
$module->id->range('1-5');
$module->root->range('1{3},2{2}');
$module->name->range('模块1,模块2,模块3,模块4,模块5');
$module->type->range('bug');
$module->deleted->range('0');
$module->gen(5);

su('admin');

global $tester;
$bugTest = new bugZenTest();

$bug1 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(1)->fetch();
$bug2 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(2)->fetch();
$bug3 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(3)->fetch();
$bug6 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(6)->fetch();
$bug7 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(7)->fetch();
$bug8 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(8)->fetch();
$bug10 = $tester->dao->select('*')->from(TABLE_BUG)->where('id')->eq(10)->fetch();

r($bugTest->getBatchResolveVarsTest(array($bug1))) && p('1') && e('tester1');
r($bugTest->getBatchResolveVarsTest(array($bug1, $bug2, $bug3))) && p('1') && e('tester1');
r($bugTest->getBatchResolveVarsTest(array($bug6, $bug7, $bug8))) && p('1') && e('tester2');
r($bugTest->getBatchResolveVarsTest(array($bug10))) && p('1') && e('tester3');
r(is_array($bugTest->getBatchResolveVarsTest(array($bug1, $bug2)))) && p() && e('1');