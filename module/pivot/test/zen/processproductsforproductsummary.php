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

// 测试步骤2：无计划产品处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin', 'status' => 'normal', 'type' => 'normal')
))) && p('0:planTitle,planBegin,planEnd,storyDraft,storyReviewing,storyActive,storyChanging,storyClosed,storyTotal') && e(',,,0,0,0,0,0,0');

// 测试步骤3：单个产品单个计划处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '计划1', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array('active' => 5, 'draft' => 2))
    ))
))) && p('0:rowspan,planTitle,planBegin,planEnd,storyActive,storyDraft,storyTotal') && e('1,计划1,2024-01-01,2024-12-31,5,2,7');

// 测试步骤4：单个产品多个计划处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 2, 'name' => '产品2', 'PO' => 'user1', 'plans' => array(
        (object)array('title' => '第一阶段', 'begin' => '2024-01-01', 'end' => '2024-06-30', 'status' => array('active' => 3, 'draft' => 1)),
        (object)array('title' => '第二阶段', 'begin' => '2024-07-01', 'end' => '2024-12-31', 'status' => array('closed' => 2, 'reviewing' => 1))
    ))
))) && p('0:rowspan,planTitle,storyActive,storyTotal;1:planTitle,storyClosed,storyReviewing,storyTotal') && e('2,第一阶段,3,4;第二阶段,2,1,3');

// 测试步骤5：未来时间计划处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 3, 'name' => '产品3', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '未来计划', 'begin' => '2030-01-01', 'end' => '2030-01-01', 'status' => array('draft' => 1, 'active' => 2))
    ))
))) && p('0:planTitle,planBegin,planEnd,storyDraft,storyActive,storyTotal') && e('未来计划,待定,待定,1,2,3');

// 测试步骤6：完整需求状态统计
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 4, 'name' => '产品4', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '完整状态计划', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array(
            'draft' => 1, 'reviewing' => 2, 'active' => 3, 'changing' => 4, 'closed' => 5
        ))
    ))
))) && p('0:storyDraft,storyReviewing,storyActive,storyChanging,storyClosed,storyTotal') && e('1,2,3,4,5,15');

// 测试步骤7：空状态计划处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 5, 'name' => '产品5', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '空状态计划', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array())
    ))
))) && p('0:storyDraft,storyReviewing,storyActive,storyChanging,storyClosed,storyTotal') && e('0,0,0,0,0,0');

// 测试步骤8：多产品混合场景处理
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 6, 'name' => '无计划产品', 'PO' => 'admin'),
    (object)array('id' => 7, 'name' => '有计划产品', 'PO' => 'user1', 'plans' => array(
        (object)array('title' => '混合测试计划', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array('draft' => 1, 'active' => 2, 'closed' => 1))
    ))
))) && p('0:planTitle,storyTotal;1:rowspan,planTitle,storyDraft,storyActive,storyClosed,storyTotal') && e(',0;1,混合测试计划,1,2,1,4');

// 测试步骤9：rowspan属性验证
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 8, 'name' => 'rowspan测试产品', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '计划A', 'begin' => '2024-01-01', 'end' => '2024-04-30', 'status' => array('active' => 2)),
        (object)array('title' => '计划B', 'begin' => '2024-05-01', 'end' => '2024-08-31', 'status' => array('draft' => 1)),
        (object)array('title' => '计划C', 'begin' => '2024-09-01', 'end' => '2024-12-31', 'status' => array('closed' => 3))
    ))
))) && p('0:rowspan,planTitle;1:rowspan,planTitle;2:rowspan,planTitle') && e('3,计划A;~~,计划B;~~,计划C');

// 测试步骤10：大量计划边界测试
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 99, 'name' => '大型产品', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '第1季度计划', 'begin' => '2024-01-01', 'end' => '2024-03-31', 'status' => array('draft' => 10)),
        (object)array('title' => '第2季度计划', 'begin' => '2024-04-01', 'end' => '2024-06-30', 'status' => array('active' => 15)),
        (object)array('title' => '第3季度计划', 'begin' => '2024-07-01', 'end' => '2024-09-30', 'status' => array('closed' => 8)),
        (object)array('title' => '第4季度计划', 'begin' => '2024-10-01', 'end' => '2024-12-31', 'status' => array('changing' => 5, 'reviewing' => 3))
    ))
))) && p('count;0:rowspan;3:planTitle,storyChanging,storyReviewing,storyTotal') && e('4;4;第4季度计划,5,3,8');