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

// 测试步骤1：空数组输入测试 - 期望返回空数组
r($pivotTest->processProductsForProductSummaryTest(array())) && p() && e('0');

// 测试步骤2：无计划产品测试 - 期望返回带默认值的产品对象
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 1, 'name' => '无计划产品', 'PO' => 'admin', 'status' => 'normal', 'type' => 'normal')
))) && p('0:id,name,PO,planTitle,planBegin,planEnd,storyDraft,storyReviewing,storyActive,storyChanging,storyClosed,storyTotal') && e('1,无计划产品,admin,,,0,0,0,0,0,0');

// 测试步骤3：单计划产品测试 - 期望正确处理计划数据和故事统计
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 2, 'name' => '单计划产品', 'PO' => 'user1', 'plans' => array(
        (object)array('title' => '正常计划', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array('active' => 5, 'draft' => 2))
    ))
))) && p('0:id,name,PO,rowspan,planTitle,planBegin,planEnd,storyActive,storyDraft,storyTotal') && e('2,单计划产品,user1,1,正常计划,2024-01-01,2024-12-31,5,2,7');

// 测试步骤4：多计划产品测试 - 期望正确设置rowspan并展开多个计划
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 3, 'name' => '多计划产品', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '第一阶段', 'begin' => '2024-01-01', 'end' => '2024-06-30', 'status' => array('active' => 3, 'draft' => 1)),
        (object)array('title' => '第二阶段', 'begin' => '2024-07-01', 'end' => '2024-12-31', 'status' => array('closed' => 2, 'reviewing' => 1))
    ))
))) && p('count;0:rowspan,planTitle,storyActive,storyDraft,storyTotal;1:rowspan,planTitle,storyClosed,storyReviewing,storyTotal') && e('2;2,第一阶段,3,1,4;~~,第二阶段,2,1,3');

// 测试步骤5：未来计划时间边界测试 - 期望正确处理特殊时间标记为"待定"
r($pivotTest->processProductsForProductSummaryTest(array(
    (object)array('id' => 6, 'name' => '未来计划产品', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '未来计划', 'begin' => '2030-01-01', 'end' => '2030-01-01', 'status' => array('draft' => 1, 'active' => 2))
    ))
))) && p('0:planTitle,planBegin,planEnd,storyDraft,storyActive,storyTotal') && e('未来计划,待定,待定,1,2,3');