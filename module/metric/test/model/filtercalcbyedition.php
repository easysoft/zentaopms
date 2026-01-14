#!/usr/bin/env php
<?php

/**

title=测试 metricModel::filterCalcByEdition();
timeout=0
cid=17075

- 步骤1：开源版本过滤 @2
- 步骤2：商业版本过滤 @4
- 步骤3：空数组 @0
- 步骤4：无数据集实例 @2
- 步骤5：未配置版本 @6

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$metricTest = new metricModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->filterCalcByEditionTest(createTestData('open'))) && p() && e(2); // 步骤1：开源版本过滤
r($metricTest->filterCalcByEditionTest(createTestData('biz'))) && p() && e(4); // 步骤2：商业版本过滤
r($metricTest->filterCalcByEditionTest(array())) && p() && e(0); // 步骤3：空数组
r($metricTest->filterCalcByEditionTest(createTestDataNoDataset())) && p() && e(2); // 步骤4：无数据集实例
r($metricTest->filterCalcByEditionTest(createTestData('unknown'))) && p() && e(6); // 步骤5：未配置版本

function createTestData($edition)
{
    global $config;
    $originalEdition = $config->edition;
    $config->edition = $edition;

    $calcInstances = array();

    // 创建包含不同数据集的测试实例
    $instance1 = new stdClass();
    $instance1->dataset = 'getFeedbacks';
    $calcInstances['test1'] = $instance1;

    $instance2 = new stdClass();
    $instance2->dataset = 'getTickets';
    $calcInstances['test2'] = $instance2;

    $instance3 = new stdClass();
    $instance3->dataset = 'getUsers';
    $calcInstances['test3'] = $instance3;

    $instance4 = new stdClass();
    $instance4->dataset = 'getTasks';
    $calcInstances['test4'] = $instance4;

    $instance5 = new stdClass();
    $instance5->dataset = 'getIssues';
    $calcInstances['test5'] = $instance5;

    $instance6 = new stdClass();
    $instance6->dataset = 'getRisks';
    $calcInstances['test6'] = $instance6;

    return $calcInstances;
}

function createTestDataNoDataset()
{
    $calcInstances = array();

    // 创建不包含数据集的测试实例
    $instance1 = new stdClass();
    $calcInstances['test1'] = $instance1;

    $instance2 = new stdClass();
    $calcInstances['test2'] = $instance2;

    return $calcInstances;
}