#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::initLibCase();
timeout=0
cid=0

- 执行testcaseTest模块的initLibCaseTest方法，参数是$case, 1, 100, 50, array
 - 属性lib @1
 - 属性title @Test Case Title
 - 属性openedBy @admin
- 执行testcaseTest模块的initLibCaseTest方法，参数是$case, 1, 100, 50, $libCases
 - 属性id @10
 - 属性lastEditedBy @admin
 - 属性version @3
- 执行testcaseTest模块的initLibCaseTest方法，参数是$case, 1, 100, 50, array 属性module @0
- 执行testcaseTest模块的initLibCaseTest方法，参数是$case, 1, 100, 50, array
 - 属性lib @1
 - 属性fromCaseID @1
- 执行testcaseTest模块的initLibCaseTest方法，参数是$case, 2, 200, 100, array
 - 属性lib @2
 - 属性order @200
- 执行testcaseTest模块的initLibCaseTest方法，参数是$case, 1, 100, 50, array
 - 属性title @Test Case Title
 - 属性pri @2
 - 属性type @feature
 - 属性stage @unittest
 - 属性status @normal
 - 属性color @#FF0000

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

zenData('case')->gen(0);
zenData('module')->gen(5);

su('admin');

$testcaseTest = new testcaseZenTest();

// 构造测试用例对象
$case = new stdclass();
$case->id = 1;
$case->title = 'Test Case Title';
$case->precondition = 'Test precondition';
$case->keywords = 'test,keywords';
$case->pri = 2;
$case->type = 'feature';
$case->stage = 'unittest';
$case->status = 'normal';
$case->version = 1;
$case->color = '#FF0000';
$case->module = 0;

// 测试步骤1:新增用例到库(libCases为空)
r($testcaseTest->initLibCaseTest($case, 1, 100, 50, array())) && p('lib,title,openedBy') && e('1,Test Case Title,admin');
// 测试步骤2:更新已存在的用例(libCases包含用例)
$libCases = array(1 => array(10 => (object)array('version' => 2)));
r($testcaseTest->initLibCaseTest($case, 1, 100, 50, $libCases)) && p('id,lastEditedBy,version') && e('10,admin,3');
// 测试步骤3:用例无模块(module为0)
$case->module = 0;
r($testcaseTest->initLibCaseTest($case, 1, 100, 50, array())) && p('module') && e('0');
// 测试步骤4:用例有模块(module大于0)
$case->module = 5;
r($testcaseTest->initLibCaseTest($case, 1, 100, 50, array())) && p('lib,fromCaseID') && e('1,1');
// 测试步骤5:验证order字段设置
r($testcaseTest->initLibCaseTest($case, 2, 200, 100, array())) && p('lib,order') && e('2,200');
// 测试步骤6:验证所有必需字段正确复制
r($testcaseTest->initLibCaseTest($case, 1, 100, 50, array())) && p('title,pri,type,stage,status,color') && e('Test Case Title,2,feature,unittest,normal,#FF0000');