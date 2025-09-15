#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getMaxVersionByIDList();
timeout=0
cid=0

- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array 
 - 属性1 @2.0
 - 属性2 @2.5
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是'1' 属性1 @2.0
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array  @~~
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array  @~~
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array 属性1 @2.0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$table = zenData('pivotspec');
$table->pivot->range('1{3},2{2}');  
$table->version->range('1.0,1.5,2.0,2.1,2.5');
$table->gen(5);

$pivotTest = new pivotTest();

r($pivotTest->getMaxVersionByIDListTest(array(1, 2))) && p('1,2') && e('2.0,2.5');
r($pivotTest->getMaxVersionByIDListTest('1')) && p('1') && e('2.0');  
r($pivotTest->getMaxVersionByIDListTest(array())) && p() && e('~~');
r($pivotTest->getMaxVersionByIDListTest(array(999))) && p() && e('~~');
r($pivotTest->getMaxVersionByIDListTest(array(1, 999))) && p('1') && e('2.0');