#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getMaxVersionByIDList();
timeout=0
cid=17392

- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array 
 - 属性1 @2.1
 - 属性2 @2.9
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是'1' 属性1 @2.1
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array  @0
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array  @0
- 执行pivotTest模块的getMaxVersionByIDListTest方法，参数是array 属性1 @2.1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 准备测试数据
global $tester;
$tester->dao->delete()->from(TABLE_PIVOTSPEC)->exec();

// 插入测试数据
$testData = array(
    array('pivot' => 1, 'version' => '1.0', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test1', 'desc' => 'Test desc1'),
    array('pivot' => 1, 'version' => '1.5', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test1', 'desc' => 'Test desc1'),
    array('pivot' => 1, 'version' => '2.0', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test1', 'desc' => 'Test desc1'),
    array('pivot' => 1, 'version' => '2.1', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test1', 'desc' => 'Test desc1'),
    array('pivot' => 2, 'version' => '2.5', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test2', 'desc' => 'Test desc2'),
    array('pivot' => 2, 'version' => '2.9', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test2', 'desc' => 'Test desc2'),
);

foreach($testData as $data)
{
    $tester->dao->insert(TABLE_PIVOTSPEC)->data($data)->exec();
}

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->getMaxVersionByIDListTest(array(1, 2))) && p('1,2') && e('2.1,2.9');
r($pivotTest->getMaxVersionByIDListTest('1')) && p('1') && e('2.1');
r($pivotTest->getMaxVersionByIDListTest(array())) && p() && e('0');
r($pivotTest->getMaxVersionByIDListTest(array(999))) && p() && e('0');
r($pivotTest->getMaxVersionByIDListTest(array(1, 999))) && p('1') && e('2.1');