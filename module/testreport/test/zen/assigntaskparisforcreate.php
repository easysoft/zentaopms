#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::assignTaskParisForCreate();
timeout=0
cid=19129

- 执行testreportTest模块的assignTaskParisForCreateTest方法，参数是1  @1
- 执行testreportTest模块的assignTaskParisForCreateTest方法，参数是0, '2' 属性2 @2
- 执行testreportTest模块的assignTaskParisForCreateTest方法  @1
- 执行testreportTest模块的assignTaskParisForCreateTest方法，参数是999  @999
- 执行testreportTest模块的assignTaskParisForCreateTest方法，参数是100, '5' 属性2 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

su('admin');

$testreportTest = new testreportTest();

r($testreportTest->assignTaskParisForCreateTest(1)) && p('0') && e('1');
r($testreportTest->assignTaskParisForCreateTest(0, '2')) && p('2') && e('2');
r($testreportTest->assignTaskParisForCreateTest()) && p('0') && e('1');
r($testreportTest->assignTaskParisForCreateTest(999)) && p('0') && e('999');
r($testreportTest->assignTaskParisForCreateTest(100, '5')) && p('2') && e('5');