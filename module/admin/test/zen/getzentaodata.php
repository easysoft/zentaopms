#!/usr/bin/env php
<?php

/**

title=测试 adminZen::getZentaoData();
timeout=0
cid=14988

- 执行$methodExists @1
- 执行$hasDataProperty @1
- 执行$hasPluginsProperty @1
- 执行$hasAllProperties @1
- 执行$hasDataIsBoolean @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $config;

$adminTest = new adminZenTest();

// 步骤1:验证getZentaoData方法存在且可访问
$methodExists = method_exists($adminTest, 'getZentaoDataTest') ? 1 : 0;
r($methodExists) && p() && e(1);

// 步骤2:无zentaoWebsite配置时返回对象包含hasData属性
unset($config->zentaoWebsite);
$result = $adminTest->getZentaoDataTest();
$hasDataProperty = isset($result->hasData) ? 1 : 0;
r($hasDataProperty) && p() && e(1);

// 步骤3:无zentaoWebsite配置时返回对象包含plugins属性
$hasPluginsProperty = isset($result->plugins) && is_array($result->plugins) ? 1 : 0;
r($hasPluginsProperty) && p() && e(1);

// 步骤4:返回对象包含dynamics、classes、patches属性
$hasAllProperties = (isset($result->dynamics) && isset($result->classes) && isset($result->patches)) ? 1 : 0;
r($hasAllProperties) && p() && e(1);

// 步骤5:验证hasData属性值为布尔类型
$hasDataIsBoolean = is_bool($result->hasData) ? 1 : 0;
r($hasDataIsBoolean) && p() && e(1);