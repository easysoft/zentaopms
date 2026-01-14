#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::formatCellData();
timeout=0
cid=17368

- 执行pivotTest模块的formatCellDataTest方法，参数是'test', array 第col1条的value属性 @100
- 执行pivotTest模块的formatCellDataTest方法，参数是'data', array 第item1条的value属性 @hello
- 执行pivotTest模块的formatCellDataTest方法，参数是'missing', array  @0
- 执行pivotTest模块的formatCellDataTest方法，参数是'complex', array 第array条的value属性 @/
- 执行pivotTest模块的formatCellDataTest方法，参数是'mixed', array 第scalar条的value属性 @test

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$pivotTest = new pivotModelTest();

r($pivotTest->formatCellDataTest('test', array('test' => array('col1' => 100)))) && p('col1:value') && e('100');
r($pivotTest->formatCellDataTest('data', array('data' => array('item1' => array('value' => 'hello'))))) && p('item1:value') && e('hello');
r($pivotTest->formatCellDataTest('missing', array('other' => array('col1' => 'value1')))) && p() && e('0');
r($pivotTest->formatCellDataTest('complex', array('complex' => array('array' => array('value' => array('nested' => 'data')))))) && p('array:value') && e('/');
r($pivotTest->formatCellDataTest('mixed', array('mixed' => array('scalar' => 'test', 'arr' => array('value' => 'valid'))))) && p('scalar:value') && e('test');