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

su('admin');

$pivotTest = new pivotZenTest();

r($pivotTest->processProductsForProductSummaryTest(array())) && p() && e('0');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin')
))) && p('0:planTitle,planBegin,planEnd,storyTotal') && e(',,,0');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '计划1', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array('active' => 5, 'draft' => 2))
    ))
))) && p('0:rowspan,planTitle,storyActive,storyDraft,storyTotal') && e('1,计划1,5,2,7');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '计划1', 'begin' => '2024-01-01', 'end' => '2024-06-30', 'status' => array('active' => 3)),
        (object)array('title' => '计划2', 'begin' => '2024-07-01', 'end' => '2024-12-31', 'status' => array('closed' => 2))
    ))
))) && p('0:rowspan;1:planTitle,storyClosed,storyTotal') && e('2;计划2,2,2');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '产品1', 'PO' => 'admin'),
    (object)array('id' => 2, 'name' => '产品2', 'PO' => 'user1', 'plans' => array(
        (object)array('title' => '计划A', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array('draft' => 1, 'active' => 2))
    ))
))) && p('0:planTitle,storyTotal;1:rowspan,planTitle,storyTotal') && e(',0;1,计划A,3');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 3, 'name' => '产品3', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '未来计划', 'begin' => '2030-01-01', 'end' => '2030-01-01', 'status' => array('draft' => 1))
    ))
))) && p('0:planBegin,planEnd,storyDraft') && e('future,future,1');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 4, 'name' => '产品4', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '完整计划', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array(
            'draft' => 1, 'reviewing' => 2, 'active' => 3, 'changing' => 4, 'closed' => 5
        ))
    ))
))) && p('0:storyDraft,storyReviewing,storyActive,storyChanging,storyClosed,storyTotal') && e('1,2,3,4,5,15');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 5, 'name' => '产品5', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '空状态计划', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array())
    ))
))) && p('0:storyDraft,storyReviewing,storyActive,storyChanging,storyClosed,storyTotal') && e('0,0,0,0,0,0');
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 6, 'name' => '产品6', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '计划1', 'begin' => '2024-01-01', 'end' => '2024-06-30', 'status' => array('active' => 5)),
        (object)array('title' => '计划2', 'begin' => '2024-07-01', 'end' => '2024-12-31', 'status' => array('closed' => 3))
    )),
    (object)array('id' => 7, 'name' => '产品7', 'PO' => 'user1'),
    (object)array('id' => 8, 'name' => '产品8', 'PO' => 'user2', 'plans' => array(
        (object)array('title' => '计划A', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array('draft' => 2, 'active' => 1))
    ))
))) && p('0:rowspan,storyActive;1:storyTotal;2:planTitle,storyTotal;3:rowspan,storyTotal') && e('2,5;3;,0;1,3');