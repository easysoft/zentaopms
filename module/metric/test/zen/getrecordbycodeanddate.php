#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getRecordByCodeAndDate();
timeout=0
cid=17193

- 步骤1：正常度量项查询第0条的metricCode属性 @test_metric_001
- 步骤2：无效度量编码 @0
- 步骤3：无日期类型度量项第0条的metricCode属性 @no_date_metric
- 步骤4：全类型查询但有推断 @0
- 步骤5：验证返回的数据值第0条的value属性 @100

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('metric');
$table->id->range('1-5');
$table->purpose->range('scale,quality,efficiency,performance,scale');
$table->scope->range('system,product,project,execution,system');
$table->object->range('product,project,execution,story,task');
$table->stage->range('released{4},wait');
$table->name->range('测试度量项1,测试度量项2,测试度量项3,无日期度量项,全类型度量项');
$table->code->range('test_metric_001,test_metric_002,test_metric_003,no_date_metric,all_type_metric');
$table->unit->range('个,天,小时,%,次');
$table->dateType->range('day,week,month,year,nodate');
$table->createdBy->range('admin{5}');
$table->createdDate->range("`2024-01-01 00:00:00`,`2024-01-02 00:00:00`,`2024-01-03 00:00:00`,`2024-01-04 00:00:00`,`2024-01-05 00:00:00`");
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$metricZenTest = new metricZenTest();

// 5. 创建模拟计算器对象
class MockCalc
{
    public function getResult($dateConfig)
    {
        return array(
            array('value' => 100, 'product' => 1),
            array('value' => 200, 'project' => 2), 
            array('value' => 150, 'execution' => 3)
        );
    }
}

$calc = new MockCalc();

// 6. 测试步骤
r($metricZenTest->getRecordByCodeAndDateZenTest('test_metric_001', $calc, '2024-01-01', 'single')) && p('0:metricCode') && e('test_metric_001'); // 步骤1：正常度量项查询
r($metricZenTest->getRecordByCodeAndDateZenTest('invalid_code', $calc, '2024-01-01', 'single')) && p() && e('0'); // 步骤2：无效度量编码
r($metricZenTest->getRecordByCodeAndDateZenTest('no_date_metric', $calc, '2024-01-01', 'single')) && p('0:metricCode') && e('no_date_metric'); // 步骤3：无日期类型度量项
r($metricZenTest->getRecordByCodeAndDateZenTest('all_type_metric', $calc, '2024-01-01', 'all')) && p() && e('0'); // 步骤4：全类型查询但有推断  
r($metricZenTest->getRecordByCodeAndDateZenTest('test_metric_002', $calc, '2024-01-02', 'single')) && p('0:value') && e('100'); // 步骤5：验证返回的数据值