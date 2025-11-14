#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildCardChart();
timeout=0
cid=18205

- 步骤1：测试空chart参数情况属性option @object
- 步骤2：测试chart无settings的情况属性option @object
- 步骤3：测试chart有settings但无sql的情况属性option @object
- 步骤4：测试chart有settings和sql但value设置不完整的情况属性option @object
- 步骤5：测试chart完整配置的text类型值计算属性option @object

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$screenTest = new screenTest();

// 4. 测试步骤
r($screenTest->buildCardChartTest(null, null)) && p('option') && e('object'); // 步骤1：测试空chart参数情况
r($screenTest->buildCardChartTest((object)array(), (object)array('settings' => null))) && p('option') && e('object'); // 步骤2：测试chart无settings的情况
r($screenTest->buildCardChartTest((object)array(), (object)array('settings' => '{}', 'sql' => null))) && p('option') && e('object'); // 步骤3：测试chart有settings但无sql的情况
r($screenTest->buildCardChartTest((object)array(), (object)array('settings' => '{"value":{}}', 'sql' => 'SELECT 1', 'id' => 9999))) && p('option') && e('object'); // 步骤4：测试chart有settings和sql但value设置不完整的情况
r($screenTest->buildCardChartTest((object)array(), (object)array('settings' => '{"value":{"field":"test","type":"text"}}', 'sql' => 'SELECT 1', 'id' => 1003))) && p('option') && e('object'); // 步骤5：测试chart完整配置的text类型值计算