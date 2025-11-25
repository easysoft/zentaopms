#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::prepareCasesForBathcCreate();
timeout=0
cid=15552

- 执行caselibTest模块的prepareCasesForBathcCreateTest方法，参数是1
 - 第0条的title属性 @Test Case Title 1
 - 第0条的type属性 @feature
 - 第0条的lib属性 @1
 - 第0条的project属性 @0
 - 第0条的status属性 @normal
 - 第0条的version属性 @1
- 执行caselibTest模块的prepareCasesForBathcCreateTest方法，参数是1, 'empty'  @1
- 执行caselibTest模块的prepareCasesForBathcCreateTest方法，参数是1, 'empty'  @1
- 执行caselibTest模块的prepareCasesForBathcCreateTest方法，参数是2, 'count'  @3
- 执行caselibTest模块的prepareCasesForBathcCreateTest方法，参数是3
 - 第0条的openedBy属性 @guest
 - 第0条的version属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

su('admin');

$caselibTest = new caselibTest();

// 测试步骤1：正常输入情况，包含所有必填字段
$_POST['title'] = array(0 => 'Test Case Title 1');
$_POST['type'] = array(0 => 'feature');
$_POST['pri'] = array(0 => 3);
$_POST['module'] = array(0 => 0);
$_POST['stage'] = array(0 => 'unittest');
r($caselibTest->prepareCasesForBathcCreateTest(1)) && p('0:title,type,lib,project,status,version') && e('Test Case Title 1,feature,1,0,normal,1');

// 测试步骤2：缺少title字段的情况
$_POST['title'] = array(0 => '');
$_POST['type'] = array(0 => 'feature');
r($caselibTest->prepareCasesForBathcCreateTest(1, 'empty')) && p() && e('1');

// 测试步骤3：缺少type字段的情况
$_POST['title'] = array(0 => 'Test Case Title 2');
$_POST['type'] = array(0 => '');
r($caselibTest->prepareCasesForBathcCreateTest(1, 'empty')) && p() && e('1');

// 测试步骤4：批量创建多个用例的情况
$_POST['title'] = array(0 => 'Test Case 1', 1 => 'Test Case 2', 2 => 'Test Case 3');
$_POST['type'] = array(0 => 'feature', 1 => 'performance', 2 => 'interface');
$_POST['pri'] = array(0 => 1, 1 => 2, 2 => 3);
r($caselibTest->prepareCasesForBathcCreateTest(2, 'count')) && p() && e('3');

// 测试步骤5：验证返回用例的字段完整性
$_POST['title'] = array(0 => 'Complete Test Case');
$_POST['type'] = array(0 => 'feature');
$_POST['pri'] = array(0 => 2);
$_POST['module'] = array(0 => 5);
$_POST['precondition'] = array(0 => 'Test precondition');
$_POST['keywords'] = array(0 => 'keyword1,keyword2');
$_POST['stage'] = array(0 => 'unittest');
r($caselibTest->prepareCasesForBathcCreateTest(3)) && p('0:openedBy,version') && e('guest,1');