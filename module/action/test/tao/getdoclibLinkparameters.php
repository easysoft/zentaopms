#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getDocLibLinkParameters();
timeout=0
cid=0

- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action1  @custom
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action2  @product
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action3  @execution
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action4  @project
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action5  @product

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');

$actionTest = new actionTest();

// 测试步骤1：自定义类型文档库（无product、project、execution）
$action1 = new stdClass();
$action1->objectID = 1;
$action1->product = '';
$action1->project = '';
$action1->execution = '';
r($actionTest->getDocLibLinkParametersTest($action1)) && p('0') && e('custom');

// 测试步骤2：产品类型文档库（只有product属性）
$action2 = new stdClass();
$action2->objectID = 2;
$action2->product = '3';
$action2->project = '';
$action2->execution = '';
r($actionTest->getDocLibLinkParametersTest($action2)) && p('0') && e('product');

// 测试步骤3：执行类型文档库（有execution属性，无product）
$action3 = new stdClass();
$action3->objectID = 3;
$action3->product = '';
$action3->project = '';
$action3->execution = '5';
r($actionTest->getDocLibLinkParametersTest($action3)) && p('0') && e('execution');

// 测试步骤4：项目类型文档库（只有project属性）
$action4 = new stdClass();
$action4->objectID = 4;
$action4->product = '';
$action4->project = '6';
$action4->execution = '';
r($actionTest->getDocLibLinkParametersTest($action4)) && p('0') && e('project');

// 测试步骤5：产品优先级测试（有product时优先返回product类型）
$action5 = new stdClass();
$action5->objectID = 5;
$action5->product = '7';
$action5->project = '8';
$action5->execution = '9';
r($actionTest->getDocLibLinkParametersTest($action5)) && p('0') && e('product');