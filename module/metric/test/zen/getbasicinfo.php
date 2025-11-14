#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getBasicInfo();
timeout=0
cid=17188

- 步骤1：获取完整基本信息字段数量 @9
- 步骤2：获取旧版度量项指定字段信息数量（含自动添加字段） @5
- 步骤3：获取单个字段的name属性第scope条的name属性 @度量范围
- 步骤4：测试空字段参数 @0
- 步骤5：测试包含desc和definition字段数量 @11
- 步骤6：测试只获取code字段的text属性第code条的text属性 @story_total
- 步骤7：测试旧版度量项单位显示第unit条的text属性 @个
- 步骤8：获取包含stage字段的信息第stage条的text属性 @已发布

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('metric')->loadYaml('metric_getbasicinfo', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricZenTest = new metricZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$view1 = new stdclass();
$view1->metric = new stdclass();
$view1->metric->id = 1;
$view1->metric->scope = 'system';
$view1->metric->object = 'story';
$view1->metric->purpose = 'scale';
$view1->metric->dateType = 'year';
$view1->metric->name = '需求总数';
$view1->metric->alias = 'StoryTotal';
$view1->metric->code = 'story_total';
$view1->metric->unit = 'count';
$view1->metric->stage = 'released';
$view1->metric->desc = '统计需求总数';
$view1->metric->definition = '需求总数量';
$view1->metric->builtin = '1';
$view1->metric->collectType = 'cron';

$view2 = new stdclass();
$view2->metric = new stdclass();
$view2->metric->id = 3;
$view2->metric->scope = 'project';
$view2->metric->object = 'bug';
$view2->metric->purpose = 'quality';
$view2->metric->dateType = 'week';
$view2->metric->name = '缺陷密度';
$view2->metric->alias = 'BugDensity';
$view2->metric->code = 'bug_density';
$view2->metric->unit = 'countperkloc';
$view2->metric->stage = 'wait';
$view2->metric->desc = '统计缺陷密度';
$view2->metric->definition = '缺陷数/千行代码';
$view2->metric->builtin = '0';
$view2->metric->collectType = 'manual';
$view2->metric->type = 'sql';
$view2->metric->oldUnit = '个';
$view2->metric->collectConf = new stdclass();
$view2->metric->collectConf->type = 'month';
$view2->metric->collectConf->month = '1';
$view2->metric->collectConf->week = '';
$view2->metric->execTime = '09:00';

$view3 = new stdclass();
$view3->metric = new stdclass();
$view3->metric->id = 5;
$view3->metric->scope = 'product';
$view3->metric->object = 'testcase';
$view3->metric->purpose = 'quality';
$view3->metric->dateType = 'year';
$view3->metric->name = '测试覆盖率';
$view3->metric->alias = 'TestCoverage';
$view3->metric->code = 'test_coverage';
$view3->metric->unit = 'percent';
$view3->metric->stage = 'wait';
$view3->metric->desc = '统计测试覆盖率';
$view3->metric->definition = '已测试代码行数/总代码行数';

r(count($metricZenTest->getBasicInfoZenTest($view1))) && p() && e('9'); // 步骤1：获取完整基本信息字段数量
r(count($metricZenTest->getBasicInfoZenTest($view2, 'scope,object,purpose'))) && p() && e('5'); // 步骤2：获取旧版度量项指定字段信息数量（含自动添加字段）
r($metricZenTest->getBasicInfoZenTest($view1, 'scope')) && p('scope:name') && e('度量范围'); // 步骤3：获取单个字段的name属性
r(count($metricZenTest->getBasicInfoZenTest($view1, ''))) && p() && e('0'); // 步骤4：测试空字段参数
r(count($metricZenTest->getBasicInfoZenTest($view3, 'scope,object,purpose,dateType,name,alias,code,unit,stage,desc,definition'))) && p() && e('11'); // 步骤5：测试包含desc和definition字段数量
r($metricZenTest->getBasicInfoZenTest($view1, 'code')) && p('code:text') && e('story_total'); // 步骤6：测试只获取code字段的text属性
r($metricZenTest->getBasicInfoZenTest($view2, 'unit')) && p('unit:text') && e('个'); // 步骤7：测试旧版度量项单位显示
r($metricZenTest->getBasicInfoZenTest($view1, 'stage')) && p('stage:text') && e('已发布'); // 步骤8：获取包含stage字段的信息