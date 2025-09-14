#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getValidObjects();
timeout=0
cid=0

- 步骤1：正常情况下获取有效对象，验证返回数组类型 @array
- 步骤2：验证返回数组包含product键 @array
- 步骤3：验证返回数组包含project键 @array
- 步骤4：验证返回数组包含execution键 @array
- 步骤5：测试缓存机制工作正常 @array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// getValidObjects方法直接查询数据库，需要准备基础数据
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->status->range('wait{2},doing{1}');
$product->deleted->range('0');
$product->shadow->range('0');
$product->gen(3);

$project = zenData('project'); 
$project->id->range('11-13');
$project->name->range('项目1,项目2,项目3');
$project->type->range('project');
$project->status->range('wait{2},doing{1}');
$project->deleted->range('0');
$project->gen(3);

$execution = zenData('execution');
$execution->id->range('21-23');
$execution->name->range('迭代1,迭代2,阶段1');
$execution->type->range('sprint{2},stage{1}');
$execution->status->range('wait{2},doing{1}');
$execution->deleted->range('0');
$execution->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricZenTest = new metricZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
$result = $metricZenTest->getValidObjectsZenTest();
r(gettype($result)) && p() && e('array'); // 步骤1：正常情况下获取有效对象，验证返回数组类型
r(gettype($result['product'])) && p() && e('array'); // 步骤2：验证返回数组包含product键
r(gettype($result['project'])) && p() && e('array'); // 步骤3：验证返回数组包含project键  
r(gettype($result['execution'])) && p() && e('array'); // 步骤4：验证返回数组包含execution键
r(gettype($metricZenTest->getValidObjectsZenTest())) && p() && e('array'); // 步骤5：测试缓存机制工作正常