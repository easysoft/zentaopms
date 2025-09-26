#!/usr/bin/env php
<?php

/**

title=测试 commonModel::formConfig();
timeout=0
cid=0

- 执行commonTest模块的formConfigTest方法，参数是'user', 'create'  @0
- 执行commonTest模块的formConfigTest方法，参数是'user', 'create' 第custom_field1条的type属性 @string
- 执行commonTest模块的formConfigTest方法，参数是'task', 'edit' 第custom_field1条的control属性 @input
- 执行commonTest模块的formConfigTest方法，参数是'product', 'view', 1 第custom_field1条的required属性 @~~
- 执行commonTest模块的formConfigTest方法，参数是'', ''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 创建测试实例
$commonTest = new commonTest();

global $config;
$originalEdition = $config->edition;

// 测试步骤1：测试open版本返回空数组
$config->edition = 'open';
r($commonTest->formConfigTest('user', 'create')) && p() && e('0');

// 测试步骤2：测试biz版本用户创建表单配置
$config->edition = 'biz';
r($commonTest->formConfigTest('user', 'create')) && p('custom_field1:type') && e('string');

// 测试步骤3：测试max版本任务编辑表单配置
$config->edition = 'max';
r($commonTest->formConfigTest('task', 'edit')) && p('custom_field1:control') && e('input');

// 测试步骤4：测试ipd版本产品查看表单配置
$config->edition = 'ipd';
r($commonTest->formConfigTest('product', 'view', 1)) && p('custom_field1:required') && e('~~');

// 测试步骤5：测试空参数处理
$config->edition = 'open';
r($commonTest->formConfigTest('', '')) && p() && e('0');

// 恢复原始配置
$config->edition = $originalEdition;