#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::getSiblings();
timeout=0
cid=17749

- 执行programplan模块的getSiblingsTest方法，参数是3 属性3 @4
- 执行programplan模块的getSiblingsTest方法，参数是[3, 4]
 - 属性3 @4
 - 属性4 @4
- 执行programplan模块的getSiblingsTest方法，参数是999 属性999 @0
- 执行programplan模块的getSiblingsTest方法，参数是'4' 属性4 @4
- 执行programplan模块的getSiblingsTest方法，参数是9 属性9 @3
- 执行programplan模块的getSiblingsTest方法，参数是1 属性1 @3
- 执行programplan模块的getSiblingsTest方法，参数是[7, 8]
 - 属性7 @2
 - 属性8 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目集1{1},项目集2{1},阶段a{1},阶段b{1},阶段c{1},阶段d{1},子阶段1{1},子阶段2{1},独立项目{1},删除项目{1}');
$table->type->range('program{2},stage{6},project{2}');
$table->parent->range('0{2},1{2},1{2},3{2},0{2}');
$table->deleted->range('0{9},1{1}');
$table->gen(10);

su('admin');

$programplan = new programplanModelTest();

r($programplan->getSiblingsTest(3)) && p('3') && e('4');
r($programplan->getSiblingsTest([3, 4])) && p('3,4') && e('4,4');
r($programplan->getSiblingsTest(999)) && p('999') && e('0');
r($programplan->getSiblingsTest('4')) && p('4') && e('4');
r($programplan->getSiblingsTest(9)) && p('9') && e('3');
r($programplan->getSiblingsTest(1)) && p('1') && e('3');
r($programplan->getSiblingsTest([7, 8])) && p('7,8') && e('2,2');