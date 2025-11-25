#!/usr/bin/env php
<?php

/**

title=测试 productZen::setShowErrorNoneMenu();
timeout=0
cid=17617

- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'qa', 'testcase', 0, 'mhtml'  @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'qa', 'testcase', 0, ''  @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'project', 'bug', 1, ''  @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'execution', 'testcase', 2, ''  @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'invalid', 'test', 0, ''  @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'qa', 'testsuite', 0, ''  @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'project', 'testcase', 3, ''  @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'execution', 'bug', 4, ''  @1
- 执行productTest模块的setShowErrorNoneMenuTest方法，参数是'', '', 0, ''  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->setShowErrorNoneMenuTest('qa', 'testcase', 0, 'mhtml')) && p() && e('1');
r($productTest->setShowErrorNoneMenuTest('qa', 'testcase', 0, '')) && p() && e('1');
r($productTest->setShowErrorNoneMenuTest('project', 'bug', 1, '')) && p() && e('1');
r($productTest->setShowErrorNoneMenuTest('execution', 'testcase', 2, '')) && p() && e('1');
r($productTest->setShowErrorNoneMenuTest('invalid', 'test', 0, '')) && p() && e('1');
r($productTest->setShowErrorNoneMenuTest('qa', 'testsuite', 0, '')) && p() && e('1');
r($productTest->setShowErrorNoneMenuTest('project', 'testcase', 3, '')) && p() && e('1');
r($productTest->setShowErrorNoneMenuTest('execution', 'bug', 4, '')) && p() && e('1');
r($productTest->setShowErrorNoneMenuTest('', '', 0, '')) && p() && e('1');