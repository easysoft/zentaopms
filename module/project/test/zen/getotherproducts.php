#!/usr/bin/env php
<?php

/**

title=测试 projectZen::getOtherProducts();
timeout=0
cid=0

- 执行projectTest模块的getOtherProductsTest方法，参数是array  @0
- 执行projectTest模块的getOtherProductsTest方法，参数是array 属性1_1 @Product A_Branch 1
- 执行projectTest模块的getOtherProductsTest方法，参数是array 属性1_2 @Product A_Branch 2
- 执行projectTest模块的getOtherProductsTest方法，参数是array 属性3_1 @Product C_Branch 1
- 执行projectTest模块的getOtherProductsTest方法，参数是array 属性2 @Product B

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

su('admin');

$projectTest = new projectTest();

r($projectTest->getOtherProductsTest(array(), array(), array(), array())) && p() && e('0');
r($projectTest->getOtherProductsTest(array(1 => 'Product A', 2 => 'Product B'), array(1 => array(1 => 'Branch 1', 2 => 'Branch 2')), array(), array())) && p('1_1') && e('Product A_Branch 1');
r($projectTest->getOtherProductsTest(array(1 => 'Product A', 2 => 'Product B'), array(1 => array(1 => 'Branch 1', 2 => 'Branch 2')), array(1 => array(1 => 1)), array(1 => 'Product A'))) && p('1_2') && e('Product A_Branch 2');
r($projectTest->getOtherProductsTest(array(1 => 'Product A', 2 => 'Product B', 3 => 'Product C'), array(1 => array(1 => 'Branch 1'), 3 => array(1 => 'Branch 1', 2 => 'Branch 2')), array(), array(2 => 'Product B'))) && p('3_1') && e('Product C_Branch 1');
r($projectTest->getOtherProductsTest(array(1 => 'Product A', 2 => 'Product B'), array(1 => array(1 => 'Branch 1', 2 => 'Branch 2')), array(1 => array(1 => 1)), array())) && p('2') && e('Product B');