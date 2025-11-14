#!/usr/bin/env php
<?php

/**

title=测试 transferModel::createTmpFile();
timeout=0
cid=19311

- 执行transferTest模块的createTmpFileTest方法，参数是array  @Success
- 执行transferTest模块的createTmpFileTest方法，参数是array  @Success
- 执行transferTest模块的createTmpFileTest方法，参数是array  @Success
- 执行transferTest模块的createTmpFileTest方法，参数是array  @Success
- 执行transferTest模块的createTmpFileTest方法，参数是array  @Success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/transfer.unittest.class.php';

su('admin');

$transferTest = new transferTest();

r($transferTest->createTmpFileTest(array(1 => (object)array('title' => '测试标题1', 'status' => 'active')))) && p() && e('Success');
r($transferTest->createTmpFileTest(array())) && p() && e('Success');
r($transferTest->createTmpFileTest(array(1 => (object)array('title' => '大量数据测试', 'content' => str_repeat('测试内容', 1000))))) && p() && e('Success');
r($transferTest->createTmpFileTest(array(1 => (object)array('title' => 'Test!@#$%^&*()', 'desc' => '特殊字符"\'\\/')))) && p() && e('Success');
r($transferTest->createTmpFileTest(array(2 => (object)array('title' => '覆盖测试', 'status' => 'new')))) && p() && e('Success');