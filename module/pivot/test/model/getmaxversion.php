#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getMaxVersion();
timeout=0
cid=17391

- 执行pivotTest模块的getMaxVersionTest方法，参数是1  @2.1
- 执行pivotTest模块的getMaxVersionTest方法，参数是2  @2.9
- 执行pivotTest模块的getMaxVersionTest方法，参数是3  @1.0
- 执行pivotTest模块的getMaxVersionTest方法，参数是4  @2.10
- 执行pivotTest模块的getMaxVersionTest方法，参数是999  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
global $tester;
$tester->dao->delete()->from(TABLE_PIVOTSPEC)->exec();

// 插入测试数据
$testData = array(
    // pivot 1: 有4个版本,最大为2.1
    array('pivot' => 1, 'version' => '1.0', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test1', 'desc' => 'Test desc1'),
    array('pivot' => 1, 'version' => '1.5', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test1', 'desc' => 'Test desc1'),
    array('pivot' => 1, 'version' => '2.0', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test1', 'desc' => 'Test desc1'),
    array('pivot' => 1, 'version' => '2.1', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test1', 'desc' => 'Test desc1'),
    // pivot 2: 有2个版本,最大为2.9
    array('pivot' => 2, 'version' => '2.5', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test2', 'desc' => 'Test desc2'),
    array('pivot' => 2, 'version' => '2.9', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test2', 'desc' => 'Test desc2'),
    // pivot 3: 只有1个版本
    array('pivot' => 3, 'version' => '1.0', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test3', 'desc' => 'Test desc3'),
    // pivot 4: 测试版本比较(2.10 > 2.2)
    array('pivot' => 4, 'version' => '2.2', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test4', 'desc' => 'Test desc4'),
    array('pivot' => 4, 'version' => '2.10', 'driver' => 'mysql', 'mode' => 'builder', 'name' => 'Test4', 'desc' => 'Test desc4'),
);

foreach($testData as $data)
{
    $tester->dao->insert(TABLE_PIVOTSPEC)->data($data)->exec();
}

su('admin');

$pivotTest = new pivotModelTest();

r($pivotTest->getMaxVersionTest(1)) && p() && e('2.1');
r($pivotTest->getMaxVersionTest(2)) && p() && e('2.9');
r($pivotTest->getMaxVersionTest(3)) && p() && e('1.0');
r($pivotTest->getMaxVersionTest(4)) && p() && e('2.10');
r($pivotTest->getMaxVersionTest(999)) && p() && e('0');