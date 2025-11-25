#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getPlanStatusStatistics();
timeout=0
cid=17445

- 执行$result1[1]->plans[1]->status['active']) ? $result1[1]->plans[1]->status['active'] : 0 @3
- 执行$result2[1]->plans[1]->status['draft']) ? $result2[1]->plans[1]->status['draft'] : 0 @1
- 执行$result3[5]->plans[0]->status['active']) ? $result3[5]->plans[0]->status['active'] : 0 @2
- 执行$result4[5]->plans[0]->title) ? $result4[5]->plans[0]->title :  @未计划
- 执行$result5[2]->plans[5]->status['changing']) ? $result5[2]->plans[5]->status['changing'] : 0 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zendata('product')->loadYaml('getplanstatusstatistics/product', false, 2)->gen(5);
zendata('productplan')->loadYaml('getplanstatusstatistics/productplan', false, 2)->gen(20);
zendata('story')->loadYaml('getplanstatusstatistics/story', false, 2)->gen(50);

su('admin');

$pivotTest = new pivotTaoTest();

/* 构造测试数据 */
function getProducts() {
    $product1 = new stdClass();
    $product1->id = 1;
    $product1->name = '产品1';
    $product1->plans = array();

    $product2 = new stdClass();
    $product2->id = 2;
    $product2->name = '产品2';
    $product2->plans = array();

    $product5 = new stdClass();
    $product5->id = 5;
    $product5->name = '产品5';
    $product5->plans = array();

    $plan1 = new stdClass();
    $plan1->id = 1;
    $plan1->product = 1;
    $plan1->title = '计划1';
    $plan1->begin = '2025-01-01';
    $plan1->end = '2025-12-31';

    $plan5 = new stdClass();
    $plan5->id = 5;
    $plan5->product = 2;
    $plan5->title = '计划5';
    $plan5->begin = '2025-01-01';
    $plan5->end = '2025-12-31';

    $product1->plans[1] = $plan1;
    $product2->plans[5] = $plan5;

    return array(1 => $product1, 2 => $product2, 5 => $product5);
}

function getPlans() {
    $plan1 = new stdClass();
    $plan1->id = 1;
    $plan1->product = 1;
    $plan1->title = '计划1';
    $plan1->begin = '2025-01-01';
    $plan1->end = '2025-12-31';

    $plan5 = new stdClass();
    $plan5->id = 5;
    $plan5->product = 2;
    $plan5->title = '计划5';
    $plan5->begin = '2025-01-01';
    $plan5->end = '2025-12-31';

    return array(1 => $plan1, 5 => $plan5);
}

/* 构造已计划的需求数据 - 计划1有3个active, 1个draft */
$story1 = new stdClass();
$story1->id = 1;
$story1->product = 1;
$story1->plan = '1';
$story1->status = 'active';

$story2 = new stdClass();
$story2->id = 2;
$story2->product = 1;
$story2->plan = '1';
$story2->status = 'active';

$story3 = new stdClass();
$story3->id = 3;
$story3->product = 1;
$story3->plan = '1';
$story3->status = 'active';

$story4 = new stdClass();
$story4->id = 4;
$story4->product = 1;
$story4->plan = '1';
$story4->status = 'draft';

/* 构造计划5的需求数据 - 计划5有3个changing */
$story9 = new stdClass();
$story9->id = 9;
$story9->product = 2;
$story9->plan = '5';
$story9->status = 'changing';

$story10 = new stdClass();
$story10->id = 10;
$story10->product = 2;
$story10->plan = '5';
$story10->status = 'changing';

$story11 = new stdClass();
$story11->id = 11;
$story11->product = 2;
$story11->plan = '5';
$story11->status = 'changing';

$plannedStories = array(
    1 => $story1,
    2 => $story2,
    3 => $story3,
    4 => $story4,
    9 => $story9,
    10 => $story10,
    11 => $story11
);

/* 构造未计划的需求数据 - 产品5有2个active, 1个draft */
$story46 = new stdClass();
$story46->id = 46;
$story46->product = 5;
$story46->plan = '';
$story46->status = 'active';

$story47 = new stdClass();
$story47->id = 47;
$story47->product = 5;
$story47->plan = '';
$story47->status = 'active';

$story48 = new stdClass();
$story48->id = 48;
$story48->product = 5;
$story48->plan = '';
$story48->status = 'draft';

$unplannedStories = array(
    46 => $story46,
    47 => $story47,
    48 => $story48
);

$products1 = getProducts();
$plans1 = getPlans();
$result1 = $pivotTest->getPlanStatusStatisticsTest($products1, $plans1, $plannedStories, $unplannedStories);
r(isset($result1[1]->plans[1]->status['active']) ? $result1[1]->plans[1]->status['active'] : 0) && p() && e('3');

$products2 = getProducts();
$plans2 = getPlans();
$result2 = $pivotTest->getPlanStatusStatisticsTest($products2, $plans2, $plannedStories, $unplannedStories);
r(isset($result2[1]->plans[1]->status['draft']) ? $result2[1]->plans[1]->status['draft'] : 0) && p() && e('1');

$products3 = getProducts();
$plans3 = getPlans();
$result3 = $pivotTest->getPlanStatusStatisticsTest($products3, $plans3, $plannedStories, $unplannedStories);
r(isset($result3[5]->plans[0]->status['active']) ? $result3[5]->plans[0]->status['active'] : 0) && p() && e('2');

$products4 = getProducts();
$plans4 = getPlans();
$result4 = $pivotTest->getPlanStatusStatisticsTest($products4, $plans4, $plannedStories, $unplannedStories);
r(isset($result4[5]->plans[0]->title) ? $result4[5]->plans[0]->title : '') && p() && e('未计划');

$products5 = getProducts();
$plans5 = getPlans();
$result5 = $pivotTest->getPlanStatusStatisticsTest($products5, $plans5, $plannedStories, $unplannedStories);
r(isset($result5[2]->plans[5]->status['changing']) ? $result5[2]->plans[5]->status['changing'] : 0) && p() && e('3');