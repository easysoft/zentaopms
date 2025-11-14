#!/usr/bin/env php
<?php

/**

title=测试 pivotZ// 测试步骤5：未来计划时间边界测试 - 期望正确处理特殊时间标记为“待定”
timeout=0
cid=17462

- 执行pivotTest模块的processProductsForProductSummaryTest方法，参数是$testData1  @0
- 执行pivotTest模块的processProductsForProductSummaryTest方法，参数是$testData2 
 - 第0条的id属性 @1
 - 第0条的name属性 @无计划产品
 - 第0条的PO属性 @admin
 - 第0条的planTitle属性 @
 - 第0条的planBegin属性 @
 - 第0条的planEnd属性 @0
 - 第0条的storyDraft属性 @0
 - 第0条的storyReviewing属性 @0
 - 第0条的storyActive属性 @0
 - 第0条的storyChanging属性 @0
 - 第0条的storyClosed属性 @0
 - 第0条的storyTotal属性 @
- 执行pivotTest模块的processProductsForProductSummaryTest方法，参数是$testData3 
 - 第0条的id属性 @2
 - 第0条的name属性 @单计划产品
 - 第0条的PO属性 @user1
 - 第0条的rowspan属性 @1
 - 第0条的planTitle属性 @正常计划
 - 第0条的planBegin属性 @2024-01-01
 - 第0条的planEnd属性 @2024-12-31
 - 第0条的storyActive属性 @5
 - 第0条的storyDraft属性 @2
 - 第0条的storyTotal属性 @7
- 执行pivotTest模块的processProductsForProductSummaryTest方法，参数是$testData4 
 - 属性count @2
 - 第0条的rowspan属性 @2
 - 第0条的planTitle属性 @第一阶段
 - 第0条的storyActive属性 @3
 - 第0条的storyDraft属性 @1
 - 第0条的storyTotal属性 @4
 - 第1条的rowspan属性 @~~
 - 第1条的planTitle属性 @第二阶段
 - 第1条的storyClosed属性 @2
 - 第1条的storyReviewing属性 @1
 - 第1条的storyTotal属性 @3
- 执行pivotTest模块的processProductsForProductSummaryTest方法，参数是$testData5 
 - 第0条的planTitle属性 @未来计划
 - 第0条的planBegin属性 @待定
 - 第0条的planEnd属性 @待定
 - 第0条的storyDraft属性 @1
 - 第0条的storyActive属性 @2
 - 第0条的storyTotal属性 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivotzen.unittest.class.php';

global $tester;
$tester->app->loadLang('productplan');

su('admin');

$pivotTest = new pivotZenTest();

// 测试步骤1：空数组输入测试 - 期望返回空数组
$testData1 = array();
r($pivotTest->processProductsForProductSummaryTest($testData1)) && p() && e('0');

// 测试步骤2：无计划产品测试 - 期望返回带默认值的产品对象
$testData2 = array(
    (object)array('id' => 1, 'name' => '无计划产品', 'PO' => 'admin', 'status' => 'normal', 'type' => 'normal')
);
r($pivotTest->processProductsForProductSummaryTest($testData2)) && p('0:id,name,PO,planTitle,planBegin,planEnd,storyDraft,storyReviewing,storyActive,storyChanging,storyClosed,storyTotal') && e('1,无计划产品,admin,,,0,0,0,0,0,0');

// 测试步骤3：单计划产品测试 - 期望正确处理计划数据和故事统计
$testData3 = array(
    (object)array('id' => 2, 'name' => '单计划产品', 'PO' => 'user1', 'plans' => array(
        (object)array('title' => '正常计划', 'begin' => '2024-01-01', 'end' => '2024-12-31', 'status' => array('active' => 5, 'draft' => 2))
    ))
);
r($pivotTest->processProductsForProductSummaryTest($testData3)) && p('0:id,name,PO,rowspan,planTitle,planBegin,planEnd,storyActive,storyDraft,storyTotal') && e('2,单计划产品,user1,1,正常计划,2024-01-01,2024-12-31,5,2,7');

// 测试步骤4：多计划产品测试 - 期望正确设置rowspan并展开多个计划
$testData4 = array(
    (object)array('id' => 3, 'name' => '多计划产品', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '第一阶段', 'begin' => '2024-01-01', 'end' => '2024-06-30', 'status' => array('active' => 3, 'draft' => 1)),
        (object)array('title' => '第二阶段', 'begin' => '2024-07-01', 'end' => '2024-12-31', 'status' => array('closed' => 2, 'reviewing' => 1))
    ))
);
r($pivotTest->processProductsForProductSummaryTest($testData4)) && p('count;0:rowspan,planTitle,storyActive,storyDraft,storyTotal;1:rowspan,planTitle,storyClosed,storyReviewing,storyTotal') && e('2;2,第一阶段,3,1,4;~~,第二阶段,2,1,3');

// 测试步骤5：未来计划时间边界测试 - 期望正确处理特殊时间标记为"待定"
$testData5 = array(
    (object)array('id' => 6, 'name' => '未来计划产品', 'PO' => 'admin', 'plans' => array(
        (object)array('title' => '未来计划', 'begin' => '2030-01-01', 'end' => '2030-01-01', 'status' => array('draft' => 1, 'active' => 2))
    ))
);
r($pivotTest->processProductsForProductSummaryTest($testData5)) && p('0:planTitle,planBegin,planEnd,storyDraft,storyActive,storyTotal') && e('未来计划,待定,待定,1,2,3');