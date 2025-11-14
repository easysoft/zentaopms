#!/usr/bin/env php
<?php

/**

title=测试 convertModel::saveState();
timeout=0
cid=15795

- 执行$convertTest->objectModel, 'saveState' @1
- 执行$convertTest->objectModel, 'saveState' @1
- 执行 @1
- 执行convertTest模块的saveStateTest方法  @exception:
- 执行 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

zenData('user')->gen(10);
zenData('product')->gen(5);
zenData('project')->gen(3);

su('admin');

$convertTest = new convertTest();

r(method_exists($convertTest->objectModel, 'saveState')) && p() && e('1');
r(is_callable(array($convertTest->objectModel, 'saveState'))) && p() && e('1');
r((new ReflectionMethod('convertModel', 'saveState'))->hasReturnType()) && p() && e('1');
r($convertTest->saveStateTest()) && p() && e('exception:');
r(class_exists('convertModel')) && p() && e('1');