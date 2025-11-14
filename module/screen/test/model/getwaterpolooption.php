#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getWaterPoloOption();
timeout=0
cid=18261

- 步骤1：settings为空字符串的处理属性hasOption @1
- 步骤2：settings为null的处理属性hasOption @1
- 步骤3：带year filters的处理属性hasDataset @1
- 步骤4：带dept filters的处理属性hasDataset @1
- 步骤5：带account filters的处理属性componentType @object

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备
$chart = zenData('chart');
$chart->id->range('1-5');
$chart->name->range('测试水球图{5}');
$chart->type->range('waterpolo');
$chart->sql->range('SELECT COUNT(*) as value FROM zt_user{5}');
$chart->settings->range('');
$chart->fields->range('');
$chart->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 准备基础测试对象
$baseComponent = new stdclass();
$baseComponent->chartConfig = new stdclass();
$baseComponent->chartConfig->key = 'WaterPolo';
$baseComponent->option = new stdclass();

// 准备有settings的图表对象
$chartWithSettings = new stdclass();
$chartWithSettings->id = 1;
$chartWithSettings->type = 'waterpolo';
$chartWithSettings->sql = 'SELECT 85.5 as value';
$chartWithSettings->settings = '[{"type":"liquidFill","radius":"90%"}]';
$chartWithSettings->fields = '[{"field":"value","type":"number"}]';
$chartWithSettings->driver = 'mysql';

// 准备无settings的图表对象
$chartWithoutSettings = new stdclass();
$chartWithoutSettings->id = 2;
$chartWithoutSettings->type = 'waterpolo';
$chartWithoutSettings->sql = 'SELECT COUNT(*) as value FROM zt_user';
$chartWithoutSettings->settings = '';
$chartWithoutSettings->fields = '';

// 准备null settings的图表对象
$chartWithNullSettings = new stdclass();
$chartWithNullSettings->id = 3;
$chartWithNullSettings->type = 'waterpolo';
$chartWithNullSettings->sql = '';
$chartWithNullSettings->settings = null;
$chartWithNullSettings->fields = null;

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($screenTest->getWaterPoloOptionTest($baseComponent, $chartWithoutSettings, array())) && p('hasOption') && e('1'); // 步骤1：settings为空字符串的处理
r($screenTest->getWaterPoloOptionTest($baseComponent, $chartWithNullSettings, array())) && p('hasOption') && e('1'); // 步骤2：settings为null的处理
r($screenTest->getWaterPoloOptionTest($baseComponent, $chartWithoutSettings, array('year' => '2023'))) && p('hasDataset') && e('1'); // 步骤3：带year filters的处理
r($screenTest->getWaterPoloOptionTest($baseComponent, $chartWithNullSettings, array('dept' => '1'))) && p('hasDataset') && e('1'); // 步骤4：带dept filters的处理
r($screenTest->getWaterPoloOptionTest($baseComponent, $chartWithoutSettings, array('account' => 'admin'))) && p('componentType') && e('object'); // 步骤5：带account filters的处理