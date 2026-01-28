#!/usr/bin/env php
<?php

/**

title=测试 bugZen::setViewMenu();
timeout=0
cid=15480

- 执行bugTest模块的setViewMenuTest方法，参数是$bug, 'project'  @1
- 执行bugTest模块的setViewMenuTest方法，参数是$bug, 'execution'  @1
- 执行bugTest模块的setViewMenuTest方法，参数是$bug, 'qa'  @1
- 执行bugTest模块的setViewMenuTest方法，参数是$bug, 'devops'  @1
- 执行bugTest模块的setViewMenuTest方法，参数是$bug, 'product'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$bugTest = new bugZenTest();

$bug = new stdClass();
$bug->product = 1;
$bug->project = 1;
$bug->execution = 101;
$bug->branch = 0;

r($bugTest->setViewMenuTest($bug, 'project')) && p() && e('1');
r($bugTest->setViewMenuTest($bug, 'execution')) && p() && e('1');
r($bugTest->setViewMenuTest($bug, 'qa')) && p() && e('1');
r($bugTest->setViewMenuTest($bug, 'devops')) && p() && e('1');
r($bugTest->setViewMenuTest($bug, 'product')) && p() && e('1');