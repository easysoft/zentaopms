#!/usr/bin/env php
<?php

/**

title=测试 actionTao::getDocLibLinkParameters();
timeout=0
cid=0

- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action1  @custom
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action2  @project
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action3  @execution
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action4  @product
- 执行actionTest模块的getDocLibLinkParametersTest方法，参数是$action5  @custom

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('doclib')->gen(5);

su('admin');

$actionTest = new actionTest();

$action1 = new stdClass();
$action1->objectID = 1;
$action1->product = '';
$action1->project = '';
$action1->execution = '';

$action2 = new stdClass();
$action2->objectID = 1;
$action2->product = '';
$action2->project = '1';
$action2->execution = '';

$action3 = new stdClass();
$action3->objectID = 1;
$action3->product = '';
$action3->project = '';
$action3->execution = '1';

$action4 = new stdClass();
$action4->objectID = 1;
$action4->product = '1';
$action4->project = '';
$action4->execution = '';

$action5 = new stdClass();
$action5->objectID = 1;
$action5->product = '';
$action5->project = '';
$action5->execution = '';

r($actionTest->getDocLibLinkParametersTest($action1)) && p('0') && e('custom');
r($actionTest->getDocLibLinkParametersTest($action2)) && p('0') && e('project');
r($actionTest->getDocLibLinkParametersTest($action3)) && p('0') && e('execution');
r($actionTest->getDocLibLinkParametersTest($action4)) && p('0') && e('product');
r($actionTest->getDocLibLinkParametersTest($action5)) && p('0') && e('custom');