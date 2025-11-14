#!/usr/bin/env php
<?php

/**

title=测试 projectZen::buildProductForm();
timeout=0
cid=17930

- 执行projectzenTest模块的buildProductFormTest方法，参数是1, array 属性currentProducts @1
- 执行projectzenTest模块的buildProductFormTest方法，参数是2, array 属性currentProducts @0
- 执行projectzenTest模块的buildProductFormTest方法，参数是3, array 属性otherProducts @1
- 执行projectzenTest模块的buildProductFormTest方法，参数是null, array 属性error @Invalid project parameter
- 执行projectzenTest模块的buildProductFormTest方法，参数是4, array_fill 属性currentProducts @50

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

$table = zenData('project');
$table->id->range('1-5');
$table->name->range('Project1,Project2,Project3,Project4,Project5');
$table->parent->range('1,2,1,2,1');
$table->type->range('project');
$table->status->range('doing');
$table->deleted->range('0');
$table->gen(5);

su('admin');

$projectzenTest = new projectzenTest();

r($projectzenTest->buildProductFormTest(1, array(1 => 'Product1', 2 => 'Product2'), array(1, 2), array(1 => array(1 => 1)), array(1 => (object)array('id' => 1, 'name' => 'Product1')))) && p('currentProducts') && e('1');
r($projectzenTest->buildProductFormTest(2, array(), array(), array(), array())) && p('currentProducts') && e('0');
r($projectzenTest->buildProductFormTest(3, array(1 => 'Product1'), array(), array(), array())) && p('otherProducts') && e('1');
r($projectzenTest->buildProductFormTest(null, array(1 => 'Product1'), array(1), array(), array())) && p('error') && e('Invalid project parameter');
r($projectzenTest->buildProductFormTest(4, array_fill(1, 100, 'Product'), array_fill(1, 100, 1), array(), array())) && p('currentProducts') && e('50');