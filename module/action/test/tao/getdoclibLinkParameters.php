#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getDocLibLinkParameters();
timeout=0
cid=0

- 测试1：custom类型 @custom
- 测试2：execution类型 @execution
- 测试3：project类型 @project
- 测试4：product类型 @product
- 测试5：product优先 @product
- 测试6：execution类型libObjectID为空 @0
- 测试7：project类型libObjectID为空 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');

$actionTest = new actionTest();

// 测试数据1：custom类型（所有字段为空）
$action1 = new stdClass();
$action1->objectID = 1;
$action1->project = '';
$action1->execution = '';
$action1->product = '';

// 测试数据2：execution类型
$action2 = new stdClass();
$action2->objectID = 2;
$action2->project = '';
$action2->execution = '5';
$action2->product = '';

// 测试数据3：project类型
$action3 = new stdClass();
$action3->objectID = 3;
$action3->project = '10';
$action3->execution = '';
$action3->product = '';

// 测试数据4：product类型
$action4 = new stdClass();
$action4->objectID = 4;
$action4->project = '';
$action4->execution = '';
$action4->product = ',15,20,';

// 测试数据5：product优先（product、project、execution都有值）
$action5 = new stdClass();
$action5->objectID = 5;
$action5->project = '25';
$action5->execution = '30';
$action5->product = ',35,';

// 测试数据6：execution类型但libObjectID为空（空字符串trim后为空）
$action6 = new stdClass();
$action6->objectID = 6;
$action6->project = '';
$action6->execution = ',,';
$action6->product = '';

// 测试数据7：project类型但libObjectID为空
$action7 = new stdClass();
$action7->objectID = 7;
$action7->project = ',';
$action7->execution = '';
$action7->product = '';

r($actionTest->getDocLibLinkParametersTest($action1)) && p('0') && e('custom'); // 测试1：custom类型
r($actionTest->getDocLibLinkParametersTest($action2)) && p('0') && e('execution'); // 测试2：execution类型
r($actionTest->getDocLibLinkParametersTest($action3)) && p('0') && e('project'); // 测试3：project类型
r($actionTest->getDocLibLinkParametersTest($action4)) && p('0') && e('product'); // 测试4：product类型
r($actionTest->getDocLibLinkParametersTest($action5)) && p('0') && e('product'); // 测试5：product优先
r($actionTest->getDocLibLinkParametersTest($action6)) && p() && e('0'); // 测试6：execution类型libObjectID为空
r($actionTest->getDocLibLinkParametersTest($action7)) && p() && e('0'); // 测试7：project类型libObjectID为空