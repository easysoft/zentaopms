#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createSystem();
timeout=0
cid=15840

- 执行convertTest模块的createSystemTest方法，参数是1
 - 属性id @1
 - 属性name @正常产品1
- 执行convertTest模块的createSystemTest方法，参数是2
 - 属性id @2
 - 属性name @正常产品2
- 执行convertTest模块的createSystemTest方法，参数是3
 - 属性id @3
 - 属性name @正常产品3
- 执行convertTest模块的createSystemTest方法，参数是4
 - 属性id @4
 - 属性name @正常产品4
- 执行convertTest模块的createSystemTest方法，参数是5
 - 属性id @5
 - 属性name @正常产品5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('product')->gen(10);
zenData('system')->gen(0);

su('admin');

$convertTest = new convertTaoTest();

r($convertTest->createSystemTest(1)) && p('id,name') && e('1,正常产品1');
r($convertTest->createSystemTest(2)) && p('id,name') && e('2,正常产品2');
r($convertTest->createSystemTest(3)) && p('id,name') && e('3,正常产品3');
r($convertTest->createSystemTest(4)) && p('id,name') && e('4,正常产品4');
r($convertTest->createSystemTest(5)) && p('id,name') && e('5,正常产品5');
