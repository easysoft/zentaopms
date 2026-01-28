#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getActiveProductCard();
timeout=0
cid=18232

- 测试2025年1月的活跃产品数第0条的count属性 @5
- 测试2025年2月的活跃产品数第0条的count属性 @3
- 测试2024年12月的活跃产品数第0条的count属性 @1
- 测试2025年3月无活跃产品第0条的count属性 @0
- 测试2024年6月无活跃产品第0条的count属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品{0}');
$product->code->range('product{0}');
$product->shadow->range('0{8},1{2}');
$product->deleted->range('0{8},1{2}');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(10);

$action = zenData('action');
$action->id->range('1-18');
$action->objectType->range('story{14},bug{2},project{1},task{1}');
$action->objectID->range('1-100');
$action->product->range(',1,,2,,3,,4,,5,,6,,7,,8,,1,,9,,10,,9,,10,,9,,9,,9,,10,');
$action->date->range('`2025-01-15 10:00:00`,`2025-01-15 11:00:00`,`2025-01-15 12:00:00`,`2025-01-15 13:00:00`,`2025-01-15 14:00:00`,`2025-02-10 10:00:00`,`2025-02-10 11:00:00`,`2025-02-10 12:00:00`,`2024-12-01 10:00:00`,`2024-12-01 11:00:00`,`2024-12-01 12:00:00`,`2025-03-10 10:00:00`,`2025-03-10 11:00:00`,`2024-06-10 10:00:00`,`2024-06-10 11:00:00`,`2024-06-10 12:00:00`,`2024-06-10 13:00:00`,`2024-06-10 14:00:00`');
$action->actor->range('admin');
$action->action->range('opened');
$action->gen(18);

su('admin');

$screenTest = new screenModelTest();

r($screenTest->getActiveProductCardTest('2025', '1')) && p('0:count') && e('5'); // 测试2025年1月的活跃产品数
r($screenTest->getActiveProductCardTest('2025', '2')) && p('0:count') && e('3'); // 测试2025年2月的活跃产品数
r($screenTest->getActiveProductCardTest('2024', '12')) && p('0:count') && e('1'); // 测试2024年12月的活跃产品数
r($screenTest->getActiveProductCardTest('2025', '3')) && p('0:count') && e('0'); // 测试2025年3月无活跃产品
r($screenTest->getActiveProductCardTest('2024', '6')) && p('0:count') && e('0'); // 测试2024年6月无活跃产品