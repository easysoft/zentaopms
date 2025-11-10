#!/usr/bin/env php
<?php

/**

title=测试 productZen::getFormFields4Activate();
timeout=0
cid=0

- 测试步骤1:检查status字段的类型第status条的type属性 @string
- 测试步骤2:检查status字段的控件类型第status条的control属性 @hidden
- 测试步骤3:检查status字段的默认值第status条的default属性 @normal
- 测试步骤4:检查comment字段的类型第comment条的type属性 @string
- 测试步骤5:检查comment字段的控件类型第comment条的control属性 @editor
- 测试步骤6:检查comment字段的宽度第comment条的width属性 @full
- 测试步骤7:检查comment字段是否必填第comment条的required属性 @~~
- 测试步骤8:检查comment字段的默认值第comment条的default属性 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->getFormFields4ActivateTest()) && p('status:type') && e('string'); // 测试步骤1:检查status字段的类型
r($productTest->getFormFields4ActivateTest()) && p('status:control') && e('hidden'); // 测试步骤2:检查status字段的控件类型
r($productTest->getFormFields4ActivateTest()) && p('status:default') && e('normal'); // 测试步骤3:检查status字段的默认值
r($productTest->getFormFields4ActivateTest()) && p('comment:type') && e('string'); // 测试步骤4:检查comment字段的类型
r($productTest->getFormFields4ActivateTest()) && p('comment:control') && e('editor'); // 测试步骤5:检查comment字段的控件类型
r($productTest->getFormFields4ActivateTest()) && p('comment:width') && e('full'); // 测试步骤6:检查comment字段的宽度
r($productTest->getFormFields4ActivateTest()) && p('comment:required') && e('~~'); // 测试步骤7:检查comment字段是否必填
r($productTest->getFormFields4ActivateTest()) && p('comment:default') && e('~~'); // 测试步骤8:检查comment字段的默认值