#!/usr/bin/env php
<?php

/**

title=测试 metricModel::updateMetricDate();
timeout=0
cid=17159

- 步骤1：正常情况 - 更新 createdDate 为 null 的记录属性updated @5
- 步骤2：验证更新后无 null 记录属性after @0
- 步骤3：再次执行应该无更新记录属性updated @0
- 步骤4：空表测试
 - 属性before @0
 - 属性after @0
 - 属性updated @0
- 步骤5：混合数据测试属性updated @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 使用手工创建测试数据方式，因为 zendata 对 NULL 日期字段的处理有问题
global $tester;

// 清理已有数据
$tester->dao->delete()->from(TABLE_METRIC)->exec();

// 手工插入测试数据
for($i = 1; $i <= 10; $i++)
{
    $metric = new stdClass();
    $metric->id = $i;
    $metric->name = "test_metric_$i";
    $metric->code = "test_code_$i";
    $metric->purpose = 'quality';
    $metric->scope = 'system';
    $metric->object = 'bug';
    $metric->createdBy = 'admin';
    $metric->stage = 'wait';
    $metric->type = 'php';
    // 前5条记录的 createdDate 设置为 null，后5条设置为具体时间
    if($i <= 5)
    {
        $metric->createdDate = null;
    }
    else
    {
        $metric->createdDate = '2023-01-01 10:00:00';
    }
    
    $tester->dao->insert(TABLE_METRIC)->data($metric)->exec();
}

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($metricTest->updateMetricDateTest()) && p('updated') && e('5'); // 步骤1：正常情况 - 更新 createdDate 为 null 的记录
r($metricTest->updateMetricDateTest()) && p('after') && e('0'); // 步骤2：验证更新后无 null 记录
r($metricTest->updateMetricDateTest()) && p('updated') && e('0'); // 步骤3：再次执行应该无更新记录

// 清理数据后测试空表
$tester->dao->delete()->from(TABLE_METRIC)->exec();
r($metricTest->updateMetricDateTest()) && p('before,after,updated') && e('0,0,0'); // 步骤4：空表测试

// 重新生成测试数据，测试混合情况
$tester->dao->delete()->from(TABLE_METRIC)->exec();
for($i = 1; $i <= 5; $i++)
{
    $metric = new stdClass();
    $metric->id = $i;
    $metric->name = "metric_test_$i";
    $metric->code = "test_code_$i";
    $metric->purpose = 'quality';
    $metric->scope = 'system';
    $metric->object = 'bug';
    $metric->createdBy = 'admin';
    $metric->stage = 'wait';
    $metric->type = 'php';
    // 前3条记录的 createdDate 设置为 null，后2条设置为具体时间
    if($i <= 3)
    {
        $metric->createdDate = null;
    }
    else
    {
        $metric->createdDate = '2023-01-01 10:00:00';
    }
    
    $tester->dao->insert(TABLE_METRIC)->data($metric)->exec();
}
r($metricTest->updateMetricDateTest()) && p('updated') && e('3'); // 步骤5：混合数据测试