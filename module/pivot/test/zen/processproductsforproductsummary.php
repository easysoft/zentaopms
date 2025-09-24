#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::processProductsForProductSummary();
timeout=0
cid=0

- 执行pivotTest模块的processProductsForProductSummaryTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

global $tester;
$tester->app->loadLang('productplan');

su('admin');

$pivotTest = new pivotZenTest();

// 测试步骤1：空产品数组输入
r($pivotTest->processProductsForProductSummaryTest(array())) && p() && e('0');

// 测试步骤2：无计划产品的处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin')
))) && p('0:planTitle,planBegin,planEnd,storyTotal') && e(',,,0');

// 测试步骤3：单个产品单个计划的处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '计划1', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array('active' => 5, 'draft' => 2))
    ))
))) && p('0:rowspan,planTitle,storyActive,storyDraft,storyTotal') && e('1,计划1,5,2,7');

// 测试步骤4：单个产品多个计划的处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '计划1', 'begin' => '2024-01-01', 'end' => '2024-06-30', 'status' => array('active' => 3)),
        (object)array('title' => '计划2', 'begin' => '2024-07-01', 'end' => '2024-12-31', 'status' => array('closed' => 2))
    ))
))) && p('0:rowspan;1:planTitle,storyClosed,storyTotal') && e('2;计划2,2,2');

// 测试步骤5：多个产品混合场景的处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin'),
    (object)array('id' => 2, 'name' => '产品2', 'PO' => 'user1', 'plans' => array(
        (object)array('title' => '计划A', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array('draft' => 1, 'active' => 2))
    ))
))) && p('0:planTitle,storyTotal;1:rowspan,planTitle,storyTotal') && e(',0;1,计划A,3');

// 测试步骤6：未来时间计划的处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 3, 'name' => '产品3', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '未来计划', 'begin' => '2030-01-01', 'end' => '2030-01-01', 'status' => array('draft' => 1))
    ))
))) && p('0:planBegin,planEnd,storyDraft') && e('待定,待定,1');

// 测试步骤7：完整需求状态计划的处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 4, 'name' => '产品4', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '完整计划', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array(
            'draft' => 1, 'reviewing' => 2, 'active' => 3, 'changing' => 4, 'closed' => 5
        ))
    ))
))) && p('0:storyDraft,storyReviewing,storyActive,storyChanging,storyClosed,storyTotal') && e('1,2,3,4,5,15');

// 测试步骤8：空状态计划的处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 5, 'name' => '产品5', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '空状态计划', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array())
    ))
))) && p('0:storyDraft,storyReviewing,storyActive,storyChanging,storyClosed,storyTotal') && e('0,0,0,0,0,0');

// 测试步骤9：多个产品多个计划的复杂场景
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品A', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '阶段1', 'begin' => '2024-01-01', 'end' => '2024-03-31', 'status' => array('active' => 2)),
        (object)array('title' => '阶段2', 'begin' => '2024-04-01', 'end' => '2024-06-30', 'status' => array('draft' => 1, 'closed' => 3))
    )),
    (object)array('id' => 2, 'name' => '产品B', 'PO' => 'user1', 'plans' => array(
        (object)array('title' => '版本1', 'begin' => '2024-02-01', 'end' => '2024-05-31', 'status' => array('reviewing' => 2, 'changing' => 1))
    ))
))) && p('count') && e('3');

// 测试步骤10：边界值测试 - 大量计划的产品
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 99, 'name' => '大型产品', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '计划1', 'begin' => '2024-01-01', 'end' => '2024-02-29', 'status' => array('draft' => 10)),
        (object)array('title' => '计划2', 'begin' => '2024-03-01', 'end' => '2024-04-30', 'status' => array('active' => 15)),
        (object)array('title' => '计划3', 'begin' => '2024-05-01', 'end' => '2024-06-30', 'status' => array('closed' => 8)),
        (object)array('title' => '计划4', 'begin' => '2024-07-01', 'end' => '2024-08-31', 'status' => array('changing' => 5)),
        (object)array('title' => '计划5', 'begin' => '2024-09-01', 'end' => '2024-10-31', 'status' => array('reviewing' => 3))
    ))
))) && p('0:rowspan;4:planTitle,storyReviewing') && e('5;计划5,3');