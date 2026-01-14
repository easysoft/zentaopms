#!/usr/bin/env php
<?php

/**

title=测试 devModel::trimSpace();
timeout=0
cid=16021

- 执行devTest模块的trimSpaceTest方法，参数是'* test '  @test
- 执行devTest模块的trimSpaceTest方法，参数是" \t\n\r * hello world \t\n\r "  @hello world
- 执行devTest模块的trimSpaceTest方法，参数是''  @0
- 执行devTest模块的trimSpaceTest方法，参数是'* \t\n\r '  @\t\n\r
- 执行devTest模块的trimSpaceTest方法，参数是'normal string'  @normal string

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$devTest = new devModelTest();

r($devTest->trimSpaceTest('* test ')) && p() && e('test');
r($devTest->trimSpaceTest(" \t\n\r * hello world \t\n\r ")) && p() && e('hello world');
r($devTest->trimSpaceTest('')) && p() && e('0');
r($devTest->trimSpaceTest('* \t\n\r ')) && p() && e('\t\n\r');
r($devTest->trimSpaceTest('normal string')) && p() && e('normal string');