#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getDocLibLinkParameters();
timeout=0
cid=14947

- 步骤1：正常情况 @custom
- 步骤2：边界值 @product
- 步骤3：异常输入 @execution
- 步骤4：权限验证 @project
- 步骤5：业务规则 @execution
- 步骤6：复杂业务逻辑 @product
- 步骤7：数据处理属性2 @10
- 步骤8：错误处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$actionTest = new actionTaoTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
// 测试步骤1：自定义类型文档库（无product、project、execution）
$action1 = new stdClass();
$action1->objectID = 1;
$action1->product = '';
$action1->project = '';
$action1->execution = '';
r($actionTest->getDocLibLinkParametersTest($action1)) && p('0') && e('custom'); // 步骤1：正常情况

// 测试步骤2：产品类型文档库（只有product属性）
$action2 = new stdClass();
$action2->objectID = 2;
$action2->product = '3';
$action2->project = '';
$action2->execution = '';
r($actionTest->getDocLibLinkParametersTest($action2)) && p('0') && e('product'); // 步骤2：边界值

// 测试步骤3：执行类型文档库（有execution属性）
$action3 = new stdClass();
$action3->objectID = 3;
$action3->product = '';
$action3->project = '';
$action3->execution = '5';
r($actionTest->getDocLibLinkParametersTest($action3)) && p('0') && e('execution'); // 步骤3：异常输入

// 测试步骤4：项目类型文档库（只有project属性）
$action4 = new stdClass();
$action4->objectID = 4;
$action4->product = '';
$action4->project = '6';
$action4->execution = '';
r($actionTest->getDocLibLinkParametersTest($action4)) && p('0') && e('project'); // 步骤4：权限验证

// 测试步骤5：execution优先于project的测试
$action5 = new stdClass();
$action5->objectID = 5;
$action5->product = '';
$action5->project = '8';
$action5->execution = '9';
r($actionTest->getDocLibLinkParametersTest($action5)) && p('0') && e('execution'); // 步骤5：业务规则

// 测试步骤6：优先级测试（product优先级最高）
$action6 = new stdClass();
$action6->objectID = 6;
$action6->product = '7';
$action6->project = '8';
$action6->execution = '9';
r($actionTest->getDocLibLinkParametersTest($action6)) && p('0') && e('product'); // 步骤6：复杂业务逻辑

// 测试步骤7：边界值测试（带逗号的libObjectID处理）
$action7 = new stdClass();
$action7->objectID = 7;
$action7->product = ',10,';
$action7->project = '';
$action7->execution = '';
r($actionTest->getDocLibLinkParametersTest($action7)) && p('2') && e('10'); // 步骤7：数据处理

// 测试步骤8：边界值测试（空的libObjectID导致返回false）
$action8 = new stdClass();
$action8->objectID = 8;
$action8->product = ',';
$action8->project = '';
$action8->execution = '';
r($actionTest->getDocLibLinkParametersTest($action8)) && p() && e('0'); // 步骤8：错误处理