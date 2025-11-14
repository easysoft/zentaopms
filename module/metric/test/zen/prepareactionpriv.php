#!/usr/bin/env php
<?php

/**

title=测试 metricZen::prepareActionPriv();
timeout=0
cid=17198

- 执行metricZenTest模块的prepareActionPrivZenTest方法，参数是$metrics 第0条的canEdit属性 @1
- 执行metricZenTest模块的prepareActionPrivZenTest方法，参数是$metrics 第0条的canImplement属性 @1
- 执行metricZenTest模块的prepareActionPrivZenTest方法，参数是$metrics 第3条的canDelist属性 @~~
- 执行metricZenTest模块的prepareActionPrivZenTest方法，参数是$metrics 第4条的canImplement属性 @~~
- 执行metricZenTest模块的prepareActionPrivZenTest方法，参数是$metrics 第4条的canRecalculate属性 @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metric');
$table->loadYaml('metric_prepareactionpriv', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricZenTest = new metricZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 传入度量项数组
$metrics = array();
for($i = 1; $i <= 5; $i++)
{
    $metric = new stdClass();
    $metric->id = $i;
    $metric->stage = ($i <= 3) ? 'wait' : 'released';
    $metric->builtin = ($i <= 3) ? '0' : '1';
    $metric->dateType = ($i == 5) ? 'nodate' : 'day';
    $metrics[] = $metric;
}
r($metricZenTest->prepareActionPrivZenTest($metrics)) && p('0:canEdit') && e('1');

// 步骤2：等待状态度量项权限验证
r($metricZenTest->prepareActionPrivZenTest($metrics)) && p('0:canImplement') && e('1');

// 步骤3：已发布状态度量项权限验证  
r($metricZenTest->prepareActionPrivZenTest($metrics)) && p('3:canDelist') && e('~~');

// 步骤4：内置度量项权限控制
r($metricZenTest->prepareActionPrivZenTest($metrics)) && p('4:canImplement') && e('~~');

// 步骤5：无日期类型度量项权限验证
r($metricZenTest->prepareActionPrivZenTest($metrics)) && p('4:canRecalculate') && e('~~');