#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getImportSteps();
timeout=0
cid=19092

- 执行testcaseTest模块的getImportStepsTest方法，参数是'stepDesc', $steps1, $stepData1, 1
 - 第1条的content属性 @打开登录页面
 - 第1条的type属性 @step
 - 第1条的number属性 @1
 - 第2条的content属性 @输入用户名密码
 - 第2条的type属性 @step
 - 第2条的number属性 @2
- 执行testcaseTest模块的getImportStepsTest方法，参数是'stepDesc', $steps2, $stepData2, 1
 - 第1条的type属性 @group
 - 第1.1条的type属性 @group
 - 第1.1.1条的type属性 @item
- 执行testcaseTest模块的getImportStepsTest方法，参数是'stepExpect', $steps3, $stepData3, 1
 - 第1条的content属性 @期望登录成功
 - 第1条的number属性 @1
- 执行testcaseTest模块的getImportStepsTest方法，参数是'stepDesc', $steps4, $stepData4, 1
 - 第1条的content属性 @无编号步骤
 - 第4条的content属性 @有编号步骤
 - 第4条的type属性 @step
- 执行testcaseTest模块的getImportStepsTest方法，参数是'stepDesc', $steps5, $stepData5, 1
 - 第1条的content属性 @使用中文顿号
 - 第1条的type属性 @step
 - 第2条的content属性 @使用英文点号
 - 第2条的type属性 @step
 - 第3条的content属性 @使用英文点号和空格
 - 第3条的type属性 @step

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：测试stepDesc字段单一步骤解析
$steps1 = array('1. 打开登录页面', '2. 输入用户名密码', '3. 点击登录按钮');
$stepData1 = array();
r($testcaseTest->getImportStepsTest('stepDesc', $steps1, $stepData1, 1)) && p('1:content,type,number;2:content,type,number') && e('打开登录页面,step,1;输入用户名密码,step,2');

// 步骤2：测试多层级步骤编号解析
$steps2 = array('1. 主步骤', '1.1. 子步骤1', '1.1.1. 孙步骤', '1.2. 子步骤2');
$stepData2 = array();
r($testcaseTest->getImportStepsTest('stepDesc', $steps2, $stepData2, 1)) && p('1:type;1.1:type;1.1.1:type') && e('group,group,item');

// 步骤3：测试stepExpect字段与已有stepData匹配
$steps3 = array('期望登录成功');
$stepData3 = array(1 => array('desc' => array('1' => array('content' => '输入账号密码'))));
r($testcaseTest->getImportStepsTest('stepExpect', $steps3, $stepData3, 1)) && p('1:content,number') && e('期望登录成功,1');

// 步骤4：测试空步骤和无效格式处理
$steps4 = array('', '  ', '无编号步骤', '4. 有编号步骤');
$stepData4 = array();
r($testcaseTest->getImportStepsTest('stepDesc', $steps4, $stepData4, 1)) && p('1:content;4:content,type') && e('无编号步骤;有编号步骤,step');

// 步骤5：测试不同分隔符支持和编号识别
$steps5 = array('1、使用中文顿号', '2.使用英文点号', '3. 使用英文点号和空格');
$stepData5 = array();
r($testcaseTest->getImportStepsTest('stepDesc', $steps5, $stepData5, 1)) && p('1:content,type;2:content,type;3:content,type') && e('使用中文顿号,step;使用英文点号,step;使用英文点号和空格,step');