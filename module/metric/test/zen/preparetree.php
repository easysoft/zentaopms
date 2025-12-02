#!/usr/bin/env php
<?php

/**

title=测试 metricZen::prepareTree();
timeout=0
cid=17203

- 步骤1：正常情况，验证产品对象节点第product条的id属性 @product
- 步骤2：空模块数组，返回空数组 @0
- 步骤3：单个模块，验证项目对象名称第project条的name属性 @项目
- 步骤4：验证子节点的父级关系第product_scale条的parent属性 @product
- 步骤5：过滤无效对象后的有效节点第product条的name属性 @产品

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metric');
$table->id->range('1-10');
$table->purpose->range('scale,quality,efficiency');
$table->scope->range('system,project,product');
$table->object->range('program,product,project,story');
$table->stage->range('wait,released');
$table->name->range('测试度量1,测试度量2,测试度量3,测试度量4,测试度量5');
$table->code->range('test_metric_1,test_metric_2,test_metric_3,test_metric_4,test_metric_5');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricZenTest = new metricZenTest();

// 准备测试数据
$modules1 = array(
    (object)array('object' => 'product', 'purpose' => 'scale'),
    (object)array('object' => 'project', 'purpose' => 'qc'),
    (object)array('object' => 'story', 'purpose' => 'rate'),
    (object)array('object' => 'product', 'purpose' => 'qc')
);

$modules2 = array();

$modules3 = array(
    (object)array('object' => 'project', 'purpose' => 'scale')
);

$modules4 = array(
    (object)array('object' => 'product', 'purpose' => 'scale'),
    (object)array('object' => 'product', 'purpose' => 'qc'),
    (object)array('object' => 'product', 'purpose' => 'rate')
);

$modules5 = array(
    (object)array('object' => 'nonexistent', 'purpose' => 'scale'),
    (object)array('object' => 'product', 'purpose' => 'qc')
);

// 5. 强制要求：必须包含至少5个测试步骤
r($metricZenTest->prepareTreeZenTest('system', 'wait', $modules1)) && p('product:id') && e('product'); // 步骤1：正常情况，验证产品对象节点
r($metricZenTest->prepareTreeZenTest('system', 'wait', $modules2)) && p() && e('0'); // 步骤2：空模块数组，返回空数组
r($metricZenTest->prepareTreeZenTest('project', 'released', $modules3)) && p('project:name') && e('项目'); // 步骤3：单个模块，验证项目对象名称
r($metricZenTest->prepareTreeZenTest('product', 'wait', $modules4)) && p('product_scale:parent') && e('product'); // 步骤4：验证子节点的父级关系
r($metricZenTest->prepareTreeZenTest('system', 'wait', $modules5)) && p('product:name') && e('产品'); // 步骤5：过滤无效对象后的有效节点