#!/usr/bin/env php
<?php

/**

title=测试 chartModel::getFirstGroup();
timeout=0
cid=15574

- 执行chartTest模块的getFirstGroupTest方法，参数是1  @1
- 执行chartTest模块的getFirstGroupTest方法，参数是2  @32
- 执行chartTest模块的getFirstGroupTest方法，参数是999  @0
- 执行chartTest模块的getFirstGroupTest方法  @0
- 执行chartTest模块的getFirstGroupTest方法，参数是-1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 直接插入测试数据而不使用zenData
global $tester;
$dao = $tester->dao;

// 清理测试数据
$dao->delete()->from(TABLE_MODULE)->where('type')->eq('chart')->exec();

// 插入测试数据
$modules = array(
    array('id' => 1, 'root' => 1, 'branch' => 0, 'name' => '图表分组1', 'parent' => 0, 'path' => ',1,', 'grade' => 1, 'order' => 10, 'type' => 'chart', 'owner' => 'admin', 'collector' => '', 'short' => 'chart1', 'deleted' => '0'),
    array('id' => 2, 'root' => 1, 'branch' => 0, 'name' => '图表分组2', 'parent' => 0, 'path' => ',2,', 'grade' => 1, 'order' => 20, 'type' => 'chart', 'owner' => 'admin', 'collector' => '', 'short' => 'chart2', 'deleted' => '0'),
    array('id' => 32, 'root' => 2, 'branch' => 0, 'name' => '图表分组32', 'parent' => 0, 'path' => ',32,', 'grade' => 1, 'order' => 10, 'type' => 'chart', 'owner' => 'admin', 'collector' => '', 'short' => 'chart32', 'deleted' => '0')
);

foreach($modules as $module) {
    $dao->insert(TABLE_MODULE)->data($module)->exec();
}

su('admin');

$chartTest = new chartTest();

r($chartTest->getFirstGroupTest(1)) && p() && e('1');
r($chartTest->getFirstGroupTest(2)) && p() && e('32');
r($chartTest->getFirstGroupTest(999)) && p() && e('0');
r($chartTest->getFirstGroupTest(0)) && p() && e('0');
r($chartTest->getFirstGroupTest(-1)) && p() && e('0');