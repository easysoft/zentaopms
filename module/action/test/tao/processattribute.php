#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processAttribute();
timeout=0
cid=14960

- 执行actionTest模块的processAttributeTest方法，参数是'user'  @user
- 执行actionTest模块的processAttributeTest方法，参数是'testtask'  @task
- 执行actionTest模块的processAttributeTest方法，参数是''  @0
- 执行actionTest模块的processAttributeTest方法，参数是'product'  @product
- 执行actionTest模块的processAttributeTest方法，参数是'bug123'  @bug123

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$actionTest = new actionTaoTest();

r($actionTest->processAttributeTest('user')) && p() && e('user');
r($actionTest->processAttributeTest('testtask')) && p() && e('task');
r($actionTest->processAttributeTest('')) && p() && e('0');
r($actionTest->processAttributeTest('product')) && p() && e('product');
r($actionTest->processAttributeTest('bug123')) && p() && e('bug123');