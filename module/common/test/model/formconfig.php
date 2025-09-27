#!/usr/bin/env php
<?php

/**

title=测试 commonModel::formConfig();
cid=0

- 测试开源版本返回空数组 >> 期望返回空数组长度为0
- 测试企业版本正常模块方法调用 >> 期望返回表单配置数组
- 测试表单字段类型配置 >> 期望返回正确的字段类型
- 测试表单字段控制属性 >> 期望返回正确的控制类型
- 测试空参数边界情况 >> 期望正确处理空参数输入

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->formConfigTest()) && p() && e('0');
r($commonTest->formConfigTest('user', 'create')) && p() && e('0');
r($commonTest->formConfigTest('task', 'edit')) && p('custom_field1:type') && e('string');
r($commonTest->formConfigTest('product', 'view')) && p('custom_field1:control') && e('input');
r($commonTest->formConfigTest('bug', 'create', 1)) && p('custom_field1:required') && e('0');