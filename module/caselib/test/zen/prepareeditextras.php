#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::prepareEditExtras();
timeout=0
cid=15553

- 执行caselibTest模块的prepareEditExtrasTest方法，参数是$formData1, 1 
 - 属性id @1
 - 属性name @Test Case Library
- 执行caselibTest模块的prepareEditExtrasTest方法，参数是$formData2, 2 
 - 属性id @2
 - 属性uid @empty-uid
- 执行caselibTest模块的prepareEditExtrasTest方法，参数是$formData3, 3 
 - 属性id @3
 - 属性name @Another Library
- 执行caselibTest模块的prepareEditExtrasTest方法，参数是$formData4, 0 属性lastEditedBy @admin
- 执行caselibTest模块的prepareEditExtrasTest方法，参数是$formData5, 5 
 - 属性id @5
 - 属性uid @file-test-uid

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

zenData('testsuite')->gen(5);

su('admin');

$caselibTest = new caselibTest();

// 创建测试数据数组
$formData1 = array('name' => 'Test Case Library', 'desc' => 'Test Description', 'uid' => 'test-uid-123');
$formData2 = array('name' => '', 'desc' => '', 'uid' => 'empty-uid');
$formData3 = array('name' => 'Another Library', 'desc' => '', 'uid' => 'another-uid');
$formData4 = array('name' => 'Time Test Library', 'desc' => '', 'uid' => 'time-test-uid');
$formData5 = array('name' => 'File Test Library', 'desc' => '<img src="test.jpg">', 'uid' => 'file-test-uid');

r($caselibTest->prepareEditExtrasTest($formData1, 1)) && p('id,name') && e('1,Test Case Library');
r($caselibTest->prepareEditExtrasTest($formData2, 2)) && p('id,uid') && e('2,empty-uid');
r($caselibTest->prepareEditExtrasTest($formData3, 3)) && p('id,name') && e('3,Another Library');
r($caselibTest->prepareEditExtrasTest($formData4, 0)) && p('lastEditedBy') && e('admin');
r($caselibTest->prepareEditExtrasTest($formData5, 5)) && p('id,uid') && e('5,file-test-uid');