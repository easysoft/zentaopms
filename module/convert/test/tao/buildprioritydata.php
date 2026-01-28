#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildPriorityData();
timeout=0
cid=15822

- 执行convertTest模块的buildPriorityDataTest方法，参数是$testData1 属性id @1
- 执行convertTest模块的buildPriorityDataTest方法，参数是$testData1 属性pname @High
- 执行convertTest模块的buildPriorityDataTest方法，参数是$testData2 属性id @2
- 执行convertTest模块的buildPriorityDataTest方法，参数是$testData3 属性pname @~~
- 执行convertTest模块的buildPriorityDataTest方法，参数是$testData4 属性id @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

$testData1 = array('id' => 1, 'name' => 'High');
$testData2 = array('id' => 2, 'name' => 'Medium');
$testData3 = array('id' => 3);
$testData4 = array('id' => 0, 'name' => 'Low');
$testData5 = array('id' => '', 'name' => '');

r($convertTest->buildPriorityDataTest($testData1)) && p('id') && e('1');
r($convertTest->buildPriorityDataTest($testData1)) && p('pname') && e('High');
r($convertTest->buildPriorityDataTest($testData2)) && p('id') && e('2');
r($convertTest->buildPriorityDataTest($testData3)) && p('pname') && e('~~');
r($convertTest->buildPriorityDataTest($testData4)) && p('id') && e('0');