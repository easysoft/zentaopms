#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::processProductsForProductSummary();
timeout=0
cid=0

- 执行pivotTest模块的processProductsForProductSummaryTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->processProductsForProductSummaryTest(array())) && p() && e('0');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin')
))) && p('0:planTitle,storyTotal') && e(',0');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '计划1', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array('active' => 5, 'draft' => 2))
    ))
))) && p('0:rowspan,planTitle,storyActive') && e('1,计划1,5');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '计划1', 'begin' => '2024-01-01', 'end' => '2024-06-30', 'status' => array('active' => 3)),
        (object)array('title' => '计划2', 'begin' => '2024-07-01', 'end' => '2024-12-31', 'status' => array('closed' => 2))
    ))
))) && p('0:rowspan;1:planTitle,storyClosed') && e('2;计划2,2');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin'),
    (object)array('id' => 2, 'name' => '产品2', 'PO' => 'user1', 'plans' => array(
        (object)array('title' => '计划A', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array('draft' => 1, 'active' => 2))
    ))
))) && p('0:planTitle;1:rowspan,storyTotal') && e(';1,3');