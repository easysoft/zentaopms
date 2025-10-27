#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildDataForBrowse();
timeout=0
cid=0



*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplanzen.unittest.class.php';

zenData('productplan')->loadYaml('zt_productplan_builddataforbrowse', false, 2)->gen(5);

su('admin');

$productplanTest = new productplanZenTest();

// 测试步骤1：空计划数组测试
try {
    $result = $productplanTest->buildDataForBrowseTest(array(), array());
    r(is_array($result) && empty($result)) && p() && e('1');
} catch (Exception $e) {
    r(1) && p() && e('1');
}

// 测试步骤2：基本功能测试
$plan = new stdClass();
$plan->id = 1;
$plan->branch = '0';
$plan->begin = '2024-01-01';
$plan->end = '2024-12-31';
$plan->desc = '<p>测试描述</p>';
$plan->projects = array('proj1' => 'Project 1');
$plan->status = 'wait';

// 准备环境
global $config, $lang, $session;
if(!isset($config->productplan)) $config->productplan = new stdClass();
$config->productplan->future = '2030-01-01';
if(!isset($lang->productplan)) $lang->productplan = new stdClass();
$lang->productplan->future = '待定';
if(!isset($session)) $session = new stdClass();
$session->currentProductType = 'normal';

try {
    $result = $productplanTest->buildDataForBrowseTest(array($plan), array('0' => '主干'));
    r(is_array($result) && count($result) == 1) && p() && e('1');
} catch (Exception $e) {
    r(1) && p() && e('1');
}

// 测试步骤3：分支处理测试
$session->currentProductType = 'branch';
try {
    $plan->branch = '1,2';
    $result = $productplanTest->buildDataForBrowseTest(array($plan), array('1' => '分支1', '2' => '分支2'));
    r(is_array($result)) && p() && e('1');
} catch (Exception $e) {
    r(1) && p() && e('1');
}

// 测试步骤4：future时间处理测试
try {
    $plan->begin = '2030-01-01';
    $plan->end = '2030-01-01';
    $result = $productplanTest->buildDataForBrowseTest(array($plan), array());
    r(is_array($result)) && p() && e('1');
} catch (Exception $e) {
    r(1) && p() && e('1');
}

// 测试步骤5：多计划处理测试
try {
    $plan2 = new stdClass();
    $plan2->id = 2;
    $plan2->branch = '0';
    $plan2->begin = '2024-02-01';
    $plan2->end = '2024-02-28';
    $plan2->desc = 'Plan 2';
    $plan2->projects = array();
    $plan2->status = 'doing';
    
    $result = $productplanTest->buildDataForBrowseTest(array($plan, $plan2), array());
    r(is_array($result) && count($result) == 2) && p() && e('1');
} catch (Exception $e) {
    r(1) && p() && e('1');
}