#!/usr/bin/env php
<?php

/**

title=测试 jobTao::getCustomParam();
timeout=0
cid=16856

- 返回true @1
- 错误信息第paramName条的0属性 @请输入参数名称。
- 错误信息第paramName条的0属性 @参数名称应该是英文字母、数字或下划线的组合。
- 返回true @1
- 返回true @1
- 返回true @1
- 错误信息第paramName条的0属性 @参数名称应该是英文字母、数字或下划线的组合。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$jobTest = new jobTaoTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：正常参数名和值
$job1 = new stdClass();
$job1->paramName = array('env', 'version');
$job1->paramValue = array('production', '1.0.0');
$job1->custom = '';
r($jobTest->getCustomParamTest($job1)) && p() && e('1'); // 返回true

// 步骤2：参数名为空但有值
$job2 = new stdClass();
$job2->paramName = array('', 'version');
$job2->paramValue = array('test', '1.0.0');
$job2->custom = '';
r($jobTest->getCustomParamTest($job2)) && p('paramName:0') && e('请输入参数名称。'); // 错误信息

// 步骤3：参数名包含特殊字符
$job3 = new stdClass();
$job3->paramName = array('env-name', 'version');
$job3->paramValue = array('test', '1.0.0');
$job3->custom = '';
r($jobTest->getCustomParamTest($job3)) && p('paramName:0') && e('参数名称应该是英文字母、数字或下划线的组合。'); // 错误信息

// 步骤4：参数名为空且值也为空
$job4 = new stdClass();
$job4->paramName = array('', '');
$job4->paramValue = array('', '');
$job4->custom = '';
r($jobTest->getCustomParamTest($job4)) && p() && e('1'); // 返回true

// 步骤5：多个有效参数
$job5 = new stdClass();
$job5->paramName = array('env', 'version', 'debug_mode');
$job5->paramValue = array('production', '1.0.0', 'false');
$job5->custom = '';
r($jobTest->getCustomParamTest($job5)) && p() && e('1'); // 返回true

// 步骤6：参数名有效但值为空
$job6 = new stdClass();
$job6->paramName = array('env', 'empty_param');
$job6->paramValue = array('production', '');
$job6->custom = '';
r($jobTest->getCustomParamTest($job6)) && p() && e('1'); // 返回true

// 步骤7：参数名包含中文字符
$job7 = new stdClass();
$job7->paramName = array('环境', 'version');
$job7->paramValue = array('test', '1.0.0');
$job7->custom = '';
r($jobTest->getCustomParamTest($job7)) && p('paramName:0') && e('参数名称应该是英文字母、数字或下划线的组合。'); // 错误信息