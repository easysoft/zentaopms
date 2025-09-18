#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getDocLibLinkParameters();
timeout=0
cid=0

- 返回custom类型参数
 -  @custom
 - 属性1 @1
 - 属性2 @~~
- 返回product类型参数
 -  @product
 - 属性1 @2
 - 属性2 @5
- 返回project类型参数
 -  @project
 - 属性1 @3
 - 属性2 @10
- 返回execution类型参数
 -  @execution
 - 属性1 @4
 - 属性2 @15
- 返回custom类型参数
 -  @custom
 - 属性1 @5
 - 属性2 @~~
- 返回product类型 @product
- 返回custom类型参数
 -  @custom
 - 属性1 @7
 - 属性2 @~~

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$actionTest = new actionTest();

// 4. 执行测试步骤
// 步骤1：custom类型文档库，无product/project/execution
$action1 = new stdClass();
$action1->objectID = 1;
$action1->product = '';
$action1->project = 0;
$action1->execution = 0;
r($actionTest->getDocLibLinkParametersTest($action1)) && p('0,1,2') && e('custom,1,~~'); // 返回custom类型参数

// 步骤2：product类型文档库，有product属性
$action2 = new stdClass();
$action2->objectID = 2;
$action2->product = '5';
$action2->project = 0;
$action2->execution = 0;
r($actionTest->getDocLibLinkParametersTest($action2)) && p('0,1,2') && e('product,2,5'); // 返回product类型参数

// 步骤3：project类型文档库，有project属性
$action3 = new stdClass();
$action3->objectID = 3;
$action3->product = '';
$action3->project = '10';
$action3->execution = 0;
r($actionTest->getDocLibLinkParametersTest($action3)) && p('0,1,2') && e('project,3,10'); // 返回project类型参数

// 步骤4：execution类型文档库，有execution属性
$action4 = new stdClass();
$action4->objectID = 4;
$action4->product = '';
$action4->project = 0;
$action4->execution = '15';
r($actionTest->getDocLibLinkParametersTest($action4)) && p('0,1,2') && e('execution,4,15'); // 返回execution类型参数

// 步骤5：project类型文档库，但project为空字符串（应返回custom类型）
$action5 = new stdClass();
$action5->objectID = 5;
$action5->product = '';
$action5->project = '';
$action5->execution = 0;
r($actionTest->getDocLibLinkParametersTest($action5)) && p('0,1,2') && e('custom,5,~~'); // 返回custom类型参数

// 步骤6：多个product的文档库（带逗号）- 只测试类型
$action6 = new stdClass();
$action6->objectID = 6;
$action6->product = ',1,2,3,';
$action6->project = 0;
$action6->execution = 0;
r($actionTest->getDocLibLinkParametersTest($action6)) && p('0') && e('product'); // 返回product类型

// 步骤7：空action对象，都为0
$action7 = new stdClass();
$action7->objectID = 7;
$action7->product = 0;
$action7->project = 0;
$action7->execution = 0;
r($actionTest->getDocLibLinkParametersTest($action7)) && p('0,1,2') && e('custom,7,~~'); // 返回custom类型参数