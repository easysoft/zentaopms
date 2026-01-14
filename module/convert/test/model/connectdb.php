#!/usr/bin/env php
<?php

/**

title=测试 convertModel::connectDB();
timeout=0
cid=15765

- 执行$convertTest->objectModel, 'connectDB' @1
- 执行$convertTest->objectModel, 'sourceDBH' @1
- 执行$convertTest->objectModel, 'connectDB' @1
- 执行 @1
- 执行 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

r(method_exists($convertTest->objectModel, 'connectDB')) && p() && e('1');
r(property_exists($convertTest->objectModel, 'sourceDBH')) && p() && e('1');
r(is_callable(array($convertTest->objectModel, 'connectDB'))) && p() && e('1');
r(class_exists('convertModel')) && p() && e('1');
r((new ReflectionMethod('convertModel', 'connectDB'))->hasReturnType()) && p() && e('1');