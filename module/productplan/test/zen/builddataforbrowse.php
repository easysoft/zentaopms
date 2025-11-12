#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildDataForBrowse();
timeout=0
cid=0

- 执行productplanTest模块的buildDataForBrowseTest方法，参数是$emptyPlans, $branchOption  @0
- 执行branchName) ? '0' : $result[0]模块的branchName方法  @0
- 执行$result[0]->desc @这是描述
- 执行$result[0]->branchName @分支1
- 执行$result[0]->begin @待定
- 执行$result[0]->end @待定
- 执行$result[0]->branchName @分支1,分支2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productplanTest = new productplanZenTest();

// 测试场景1: 空计划数组
$emptyPlans = array();
$branchOption = array();
r(count($productplanTest->buildDataForBrowseTest($emptyPlans, $branchOption))) && p() && e('0');

// 测试场景2: normal产品类型的单个计划
global $config, $lang;
$config->productplan->future = '2030-01-01';
$lang->productplan->future = '待定';

$normalPlan = new stdClass();
$normalPlan->id = 1;
$normalPlan->product = 1;
$normalPlan->branch = '0';
$normalPlan->title = '计划1';
$normalPlan->status = 'wait';
$normalPlan->begin = '2024-01-01';
$normalPlan->end = '2024-12-31';
$normalPlan->desc = '<p>这是描述</p>';
$normalPlan->projects = array(1 => 'Project1', 2 => 'Project2');

// 设置 session 为 normal 类型产品
global $app;
$app->session->currentProductType = 'normal';

$plans = array($normalPlan);
$result = $productplanTest->buildDataForBrowseTest($plans, array());
r(empty($result[0]->branchName) ? '0' : $result[0]->branchName) && p() && e('0');
r(strip_tags($result[0]->desc)) && p() && e('这是描述');

// 测试场景3: branch产品类型的计划
$app->session->currentProductType = 'branch';

$branchPlan = new stdClass();
$branchPlan->id = 2;
$branchPlan->product = 2;
$branchPlan->branch = '1';
$branchPlan->title = '计划2';
$branchPlan->status = 'doing';
$branchPlan->begin = '2024-02-01';
$branchPlan->end = '2024-11-30';
$branchPlan->desc = '无HTML标签的描述';
$branchPlan->projects = array(3 => 'Project3');

$branchOptions = array('0' => '主干', '1' => '分支1', '2' => '分支2');
$plans = array($branchPlan);
$result = $productplanTest->buildDataForBrowseTest($plans, $branchOptions);
r($result[0]->branchName) && p() && e('分支1');

// 测试场景4: 未来日期的计划
$futurePlan = new stdClass();
$futurePlan->id = 3;
$futurePlan->product = 1;
$futurePlan->branch = '0';
$futurePlan->title = '计划3';
$futurePlan->status = 'wait';
$futurePlan->begin = '2030-01-01';
$futurePlan->end = '2030-01-01';
$futurePlan->desc = '';
$futurePlan->projects = array();

$app->session->currentProductType = 'normal';
$plans = array($futurePlan);
$result = $productplanTest->buildDataForBrowseTest($plans, array());
r($result[0]->begin) && p() && e('待定');
r($result[0]->end) && p() && e('待定');

// 测试场景5: 多分支计划
$app->session->currentProductType = 'branch';

$multiBranchPlan = new stdClass();
$multiBranchPlan->id = 4;
$multiBranchPlan->product = 3;
$multiBranchPlan->branch = '1,2';
$multiBranchPlan->title = '计划4';
$multiBranchPlan->status = 'done';
$multiBranchPlan->begin = '2024-03-01';
$multiBranchPlan->end = '2024-10-31';
$multiBranchPlan->desc = '<strong>加粗文本</strong><br>换行';
$multiBranchPlan->projects = array(4 => 'Project4', 5 => 'Project5');

$plans = array($multiBranchPlan);
$result = $productplanTest->buildDataForBrowseTest($plans, $branchOptions);
r($result[0]->branchName) && p() && e('分支1,分支2');