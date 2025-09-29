#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processWorkflowHooks();
timeout=0
cid=0

- 步骤1：正常情况测试 >> 1
- 步骤2：空结果测试 >> 0
- 步骤3：缺少step字段测试 >> 0
- 步骤4：验证hook对象action属性 >> update
- 步骤5：验证hook对象table属性 >> story

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// processWorkflowHooks方法不需要数据库表，跳过数据准备

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->processWorkflowHooksTest(
    array('results' => array('unconditional-result' => array('@attributes' => array('step' => 'step1')))),
    array('step1' => 'completed'),
    'story'
)) && p() && e('1'); // 步骤1：正常情况测试

r($convertTest->processWorkflowHooksTest(
    array(),
    array('step1' => 'completed'),
    'story'
)) && p() && e('0'); // 步骤2：空结果测试

r($convertTest->processWorkflowHooksTest(
    array('results' => array('unconditional-result' => array('@attributes' => array()))),
    array('step2' => 'in-progress'),
    'task'
)) && p() && e('0'); // 步骤3：缺少step字段测试

r($convertTest->processWorkflowHooksTest(
    array('results' => array('unconditional-result' => array('@attributes' => array('step' => 'step2')))),
    array('step2' => 'in-progress'),
    'task'
)) && p('0:action') && e('update'); // 步骤4：验证hook对象action属性

r($convertTest->processWorkflowHooksTest(
    array('results' => array('unconditional-result' => array('@attributes' => array('step' => 'step3')))),
    array('step3' => array('done', 'finished')),
    'story'
)) && p('0:table') && e('story'); // 步骤5：验证hook对象table属性