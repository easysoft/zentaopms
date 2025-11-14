#!/usr/bin/env php
<?php

/**

title=测试 dimensionModel::getFirst();
timeout=0
cid=16035

- 执行dimensionTest模块的getFirstTest方法，参数是'1, 2, 3, 4, 5' 属性name @维度1
- 执行dimensionTest模块的getFirstTest方法，参数是'1, 2, 3, 4, 5' 属性id @1
- 执行dimensionTest模块的getFirstTest方法，参数是'1, 2, 3, 4, 5' 属性code @dim1
- 执行dimensionTest模块的getFirstTest方法，参数是'1, 2, 3, 4, 5' 属性desc @描述1
- 执行dimensionTest模块的getFirstTest方法，参数是''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dimension.unittest.class.php';

$table = zenData('dimension');
$table->id->range('1-5');
$table->name->range('维度1,维度2,维度3,维度4,维度5');
$table->code->range('dim1,dim2,dim3,dim4,dim5');
$table->desc->range('描述1,描述2,描述3,描述4,描述5');
$table->createdBy->range('admin{5}');
$table->createdDate->range('`2023-01-01 10:00:00`,`2023-01-02 10:00:00`,`2023-01-03 10:00:00`,`2023-01-04 10:00:00`,`2023-01-05 10:00:00`');
$table->deleted->range('0{5}');
$table->gen(5);

su('admin');

$dimensionTest = new dimensionTest();

r($dimensionTest->getFirstTest('1,2,3,4,5')) && p('name') && e('维度1');
r($dimensionTest->getFirstTest('1,2,3,4,5')) && p('id') && e('1');
r($dimensionTest->getFirstTest('1,2,3,4,5')) && p('code') && e('dim1');
r($dimensionTest->getFirstTest('1,2,3,4,5')) && p('desc') && e('描述1');

zenData('dimension')->gen(0);
r($dimensionTest->getFirstTest('')) && p() && e('0');