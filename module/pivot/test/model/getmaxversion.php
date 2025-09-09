#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getMaxVersion();
timeout=0
cid=0

- 执行pivotTest模块的getMaxVersionTest方法，参数是1  @2.0.0
- 执行pivotTest模块的getMaxVersionTest方法，参数是2  @1.2.0
- 执行pivotTest模块的getMaxVersionTest方法，参数是999  @3.0.0
- 执行pivotTest模块的getMaxVersionTest方法，参数是100  @
- 执行pivotTest模块的getMaxVersionTest方法  @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivotspecTable = zenData('pivotspec');
$pivotspecTable->pivot->range('1{3}, 2{2}, 999{1}');
$pivotspecTable->version->range('1.0.0{1}, 1.1.0{1}, 2.0.0{1}, 1.0.1{1}, 1.2.0{1}, 3.0.0{1}');
$pivotspecTable->driver->range('mysql');
$pivotspecTable->mode->range('builder');
$pivotspecTable->name->range('测试透视表');
$pivotspecTable->gen(6);

su('admin');

$pivotTest = new pivotTest();

r($pivotTest->getMaxVersionTest(1)) && p() && e('2.0.0');
r($pivotTest->getMaxVersionTest(2)) && p() && e('1.2.0');
r($pivotTest->getMaxVersionTest(999)) && p() && e('3.0.0');
r($pivotTest->getMaxVersionTest(100)) && p() && e('');
r($pivotTest->getMaxVersionTest(0)) && p() && e('');