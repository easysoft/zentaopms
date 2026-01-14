#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildConfigurationcontextData();
timeout=0
cid=15805

- 执行convertTest模块的buildConfigurationcontextDataTest方法，参数是$fullData
 - 属性id @12345
 - 属性customfield @test.configuration.key
 - 属性fieldconfigscheme @default_scheme
 - 属性project @PROJECT_001
- 执行convertTest模块的buildConfigurationcontextDataTest方法，参数是$partialData
 - 属性id @67890
 - 属性customfield @partial.config.key
 - 属性fieldconfigscheme @~~
 - 属性project @~~
- 执行convertTest模块的buildConfigurationcontextDataTest方法，参数是$minimalData
 - 属性id @999
 - 属性customfield @~~
 - 属性fieldconfigscheme @~~
 - 属性project @~~
- 执行convertTest模块的buildConfigurationcontextDataTest方法，参数是$emptyData
 - 属性id @777
 - 属性customfield @~~
 - 属性fieldconfigscheme @~~
 - 属性project @~~
- 执行convertTest模块的buildConfigurationcontextDataTest方法，参数是$specialData
 - 属性id @config-001
 - 属性customfield @special.config."with_quotes"&<html>

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 4. 测试步骤：必须包含至少5个测试步骤

// 步骤1：完整配置数据构建
$fullData = array(
    'id' => '12345',
    'key' => 'test.configuration.key',
    'fieldconfigscheme' => 'default_scheme',
    'project' => 'PROJECT_001'
);
r($convertTest->buildConfigurationcontextDataTest($fullData)) && p('id,customfield,fieldconfigscheme,project') && e('12345,test.configuration.key,default_scheme,PROJECT_001');

// 步骤2：部分字段缺失数据构建
$partialData = array(
    'id' => '67890',
    'key' => 'partial.config.key'
);
r($convertTest->buildConfigurationcontextDataTest($partialData)) && p('id,customfield,fieldconfigscheme,project') && e('67890,partial.config.key,~~,~~');

// 步骤3：仅有ID的最小数据构建
$minimalData = array(
    'id' => '999'
);
r($convertTest->buildConfigurationcontextDataTest($minimalData)) && p('id,customfield,fieldconfigscheme,project') && e('999,~~,~~,~~');

// 步骤4：仅ID无其他字段测试
$emptyData = array(
    'id' => '777'
);
r($convertTest->buildConfigurationcontextDataTest($emptyData)) && p('id,customfield,fieldconfigscheme,project') && e('777,~~,~~,~~');

// 步骤5：特殊字符和边界值测试
$specialData = array(
    'id' => 'config-001',
    'key' => 'special.config."with_quotes"&<html>',
    'fieldconfigscheme' => 'scheme-with-dashes_and_underscores',
    'project' => 'PROJECT_WITH_SPECIAL_CHARS_123'
);
r($convertTest->buildConfigurationcontextDataTest($specialData)) && p('id,customfield') && e('config-001,special.config."with_quotes"&<html>');