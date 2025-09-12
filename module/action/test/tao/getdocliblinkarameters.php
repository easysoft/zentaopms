#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getDocLibLinkParameters();
timeout=0
cid=0

- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action1  @execution
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action2  @project
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action3  @product
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action4  @custom
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action5  @project
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action6  @custom
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action7  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');

$actionTest = new actionTest();

$action1 = new stdClass();
$action1->objectID = 1;
$action1->project = '';
$action1->execution = '1';
$action1->product = '';

$action2 = new stdClass();
$action2->objectID = 2;
$action2->project = '2';
$action2->execution = '';
$action2->product = '';

$action3 = new stdClass();
$action3->objectID = 3;
$action3->project = '';
$action3->execution = '';
$action3->product = ',3,4,';

$action4 = new stdClass();
$action4->objectID = 4;
$action4->project = '';
$action4->execution = '';
$action4->product = '';

$action5 = new stdClass();
$action5->objectID = 5;
$action5->project = '5';
$action5->execution = '';
$action5->product = '';

$action6 = new stdClass();
$action6->objectID = 6;
$action6->project = '';
$action6->execution = '';
$action6->product = '';

$action7 = new stdClass();
$action7->objectID = 7;
$action7->project = ',,';  // 包含逗号的字符串，trim后为空
$action7->execution = '';
$action7->product = '';

r($actionTest->getDocLibLinkParametersTest($action1)) && p('0') && e('execution');
r($actionTest->getDocLibLinkParametersTest($action2)) && p('0') && e('project');
r($actionTest->getDocLibLinkParametersTest($action3)) && p('0') && e('product');
r($actionTest->getDocLibLinkParametersTest($action4)) && p('0') && e('custom');
r($actionTest->getDocLibLinkParametersTest($action5)) && p('0') && e('project');
r($actionTest->getDocLibLinkParametersTest($action6)) && p('0') && e('custom');
r($actionTest->getDocLibLinkParametersTest($action7)) && p() && e('0');