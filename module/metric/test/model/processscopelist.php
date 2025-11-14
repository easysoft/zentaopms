#!/usr/bin/env php
<?php

/**

title=测试 metricModel::processScopeList();
timeout=0
cid=17152

- 步骤1：默认参数测试 @1
- 步骤2：空参数测试 @1
- 步骤3：自定义stage参数测试 @1
- 步骤4：测试featureBar配置 @1
- 步骤5：测试其他stage值 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$metric = zenData('metric');
$metric->id->range('1-20');
$metric->code->range('bug_count, story_count, task_count, project_count, user_count, system_count, product_count, execution_count');
$metric->name->range('缺陷数量, 需求数量, 任务数量, 项目数量, 用户数量, 系统度量, 产品数量, 执行数量');
$metric->scope->range('project{3}, product{3}, execution{2}, user{2}, system{5}, program{2}, other{3}');
$metric->object->range('bug, story, task, project, user, system, product, execution');
$metric->purpose->range('scale');
$metric->stage->range('released');
$metric->deleted->range('0');
$metric->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->processScopeListTest('all')) && p() && e('1'); // 步骤1：默认参数测试
r($metricTest->processScopeListTest('')) && p() && e('1'); // 步骤2：空参数测试
r($metricTest->processScopeListTest('released')) && p() && e('1'); // 步骤3：自定义stage参数测试
r($metricTest->processScopeListTest('all')) && p() && e('1'); // 步骤4：测试featureBar配置
r($metricTest->processScopeListTest('wait')) && p() && e('1'); // 步骤5：测试其他stage值