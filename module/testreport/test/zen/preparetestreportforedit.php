#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::prepareTestreportForEdit();
timeout=0
cid=19136

- 执行testreportTest模块的prepareTestreportForEditTest方法，参数是1, array
 - 属性title @Test Report
 - 属性owner @admin
- 执行testreportTest模块的prepareTestreportForEditTest方法，参数是2, array 属性hasErrors @1
- 执行testreportTest模块的prepareTestreportForEditTest方法，参数是3, array 属性hasErrors @1
- 执行testreportTest模块的prepareTestreportForEditTest方法，参数是4, array 属性hasErrors @1
- 执行testreportTest模块的prepareTestreportForEditTest方法，参数是5, array
 - 属性id @5
 - 属性title @Edit Report

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

zenData('testreport')->gen(5);

su('admin');

$testreportTest = new testreportTest();

r($testreportTest->prepareTestreportForEditTest(1, array('title' => 'Test Report', 'owner' => 'admin', 'begin' => '2024-01-01', 'end' => '2024-01-31'))) && p('title,owner') && e('Test Report,admin');
r($testreportTest->prepareTestreportForEditTest(2, array('title' => '', 'owner' => 'admin', 'begin' => '2024-01-01', 'end' => '2024-01-31'))) && p('hasErrors') && e('1');
r($testreportTest->prepareTestreportForEditTest(3, array('title' => 'Test Report', 'owner' => '', 'begin' => '2024-01-01', 'end' => '2024-01-31'))) && p('hasErrors') && e('1');
r($testreportTest->prepareTestreportForEditTest(4, array('title' => 'Test Report', 'owner' => 'admin', 'begin' => '2024-01-31', 'end' => '2024-01-01'))) && p('hasErrors') && e('1');
r($testreportTest->prepareTestreportForEditTest(5, array('title' => 'Edit Report', 'owner' => 'user'))) && p('id,title') && e('5,Edit Report');