#!/usr/bin/env php
<?php
/**

title=productModel->createSystem();
cid=0

- 测试创建产品一的应用第2条的name属性 @产品一
- 测试再次创建产品一的应用第3条的name属性 @产品一3
- 测试创建同名应用一的应用第4条的name属性 @应用一4
- 测试再次创建同名应用一的应用第5条的name属性 @应用一5
- 测试产品二的应用第6条的name属性 @产品二

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$system = zenData('system');
$system->id->range(1);
$system->product->range(1);
$system->name->range('应用一');
$system->gen(1);

$product = new productModelTest();
r($product->createSystemTest(1, '产品一')) && p('2:name') && e('产品一');  // 测试创建产品一的应用
r($product->createSystemTest(1, '产品一')) && p('3:name') && e('产品一3'); // 测试再次创建产品一的应用
r($product->createSystemTest(1, '应用一')) && p('4:name') && e('应用一4'); // 测试创建同名应用一的应用
r($product->createSystemTest(1, '应用一')) && p('5:name') && e('应用一5'); // 测试再次创建同名应用一的应用
r($product->createSystemTest(2, '产品二')) && p('6:name') && e('产品二');  // 测试产品二的应用
