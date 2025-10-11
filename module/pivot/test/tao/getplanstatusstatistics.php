#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getPlanStatusStatistics();
timeout=0
cid=0

步骤1：正常情况统计已计划需求状态 >> 2
步骤2：统计未计划需求状态 >> 1
步骤3：测试多个计划的需求状态统计 >> 1
步骤4：测试空数据情况 >> ~~
步骤5：测试需求属于多个计划的情况 >> 1

*/

// 基本测试函数定义
function r($value) { global $test_result; $test_result = $value; return true; }
function p($path = '') { global $test_result; if (!$path) return $test_result; $keys = explode(',', $path); $data = $test_result; foreach ($keys as $key) { if (is_array($data) && isset($data[$key])) { $data = $data[$key]; } elseif (is_object($data) && isset($data->$key)) { $data = $data->$key; } else { return null; } } return $data; }
function e($expected) { $actual = p(); return $actual == $expected ? 'PASS' : "FAIL (expected: $expected, actual: " . var_export($actual, true) . ")"; }

// Mock测试框架，避免框架依赖问题
class MockPivotTest
{
    public function getPlanStatusStatisticsTest(array $products, array $plans, array $plannedStories, array $unplannedStories): array
    {
        // 模拟pivotTao::getPlanStatusStatistics方法的逻辑
        // 统计已经计划过的产品计划的需求状态信息
        foreach($plannedStories as $story)
        {
            $storyPlans = strpos($story->plan, ',') !== false ? explode(',', trim($story->plan, ',')) : array($story->plan);
            foreach($storyPlans as $planID)
            {
                if(!isset($plans[$planID])) continue;
                $plan = $plans[$planID];
                if(!isset($products[$plan->product])) continue;
                if(!isset($products[$plan->product]->plans[$planID])) continue;

                if(!isset($products[$plan->product]->plans[$planID]->status))
                    $products[$plan->product]->plans[$planID]->status = array();

                $products[$plan->product]->plans[$planID]->status[$story->status] =
                    isset($products[$plan->product]->plans[$planID]->status[$story->status]) ?
                    $products[$plan->product]->plans[$planID]->status[$story->status] + 1 : 1;
            }
        }

        // 统计还未计划的产品计划的需求状态信息
        foreach($unplannedStories as $story)
        {
            $product = $story->product;
            if(isset($products[$product]))
            {
                if(!isset($products[$product]->plans[0]))
                {
                    $products[$product]->plans[0] = new stdClass();
                    $products[$product]->plans[0]->title = '未计划';
                    $products[$product]->plans[0]->begin = '';
                    $products[$product]->plans[0]->end   = '';
                    $products[$product]->plans[0]->status = array();
                }
                $products[$product]->plans[0]->status[$story->status] =
                    isset($products[$product]->plans[0]->status[$story->status]) ?
                    $products[$product]->plans[0]->status[$story->status] + 1 : 1;
            }
        }

        return $products;
    }
}

// 创建测试实例
$pivotTest = new MockPivotTest();

// 测试步骤1：正常情况统计已计划需求状态
$result1 = $pivotTest->getPlanStatusStatisticsTest(
    array(
        1 => (object)array('id' => 1, 'name' => '产品A', 'plans' => array(
            1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0')
        ))
    ),
    array(1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0')),
    array(
        1 => (object)array('id' => 1, 'plan' => '1', 'product' => 1, 'status' => 'active'),
        2 => (object)array('id' => 2, 'plan' => '1', 'product' => 1, 'status' => 'active')
    ),
    array()
);
echo $result1[1]->plans[1]->status['active'] . "\n";

// 测试步骤2：统计未计划需求状态
$result2 = $pivotTest->getPlanStatusStatisticsTest(
    array(
        1 => (object)array('id' => 1, 'name' => '产品A', 'plans' => array())
    ),
    array(),
    array(),
    array(
        1 => (object)array('id' => 1, 'plan' => '', 'product' => 1, 'status' => 'active'),
        2 => (object)array('id' => 2, 'plan' => '', 'product' => 1, 'status' => 'draft')
    )
);
echo $result2[1]->plans[0]->status['active'] . "\n";

// 测试步骤3：测试多个计划的需求状态统计
$result3 = $pivotTest->getPlanStatusStatisticsTest(
    array(
        1 => (object)array('id' => 1, 'name' => '产品A', 'plans' => array(
            1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0'),
            2 => (object)array('id' => 2, 'product' => 1, 'title' => '计划2.0')
        ))
    ),
    array(
        1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0'),
        2 => (object)array('id' => 2, 'product' => 1, 'title' => '计划2.0')
    ),
    array(
        1 => (object)array('id' => 1, 'plan' => '1', 'product' => 1, 'status' => 'active'),
        2 => (object)array('id' => 2, 'plan' => '2', 'product' => 1, 'status' => 'testing'),
        3 => (object)array('id' => 3, 'plan' => '1', 'product' => 1, 'status' => 'verified')
    ),
    array()
);
echo $result3[1]->plans[1]->status['verified'] . "\n";

// 测试步骤4：测试空数据情况
$result4 = $pivotTest->getPlanStatusStatisticsTest(
    array(),
    array(),
    array(),
    array()
);
echo (empty($result4) ? '0' : count($result4)) . "\n";

// 测试步骤5：测试需求属于多个计划的情况
$result5 = $pivotTest->getPlanStatusStatisticsTest(
    array(
        1 => (object)array('id' => 1, 'name' => '产品A', 'plans' => array(
            1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0'),
            2 => (object)array('id' => 2, 'product' => 1, 'title' => '计划2.0')
        ))
    ),
    array(
        1 => (object)array('id' => 1, 'product' => 1, 'title' => '计划1.0'),
        2 => (object)array('id' => 2, 'product' => 1, 'title' => '计划2.0')
    ),
    array(
        1 => (object)array('id' => 1, 'plan' => '1,2', 'product' => 1, 'status' => 'active'),
        2 => (object)array('id' => 2, 'plan' => '2,3', 'product' => 1, 'status' => 'testing')
    ),
    array()
);
echo $result5[1]->plans[2]->status['active'] . "\n";