#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getImportField();
timeout=0
cid=0

- 执行testcaseTest模块的getImportFieldTest方法，参数是'story', '用户登录功能 属性story @123
- 执行testcaseTest模块的getImportFieldTest方法，参数是'module', '系统管理模块 属性module @456
- 执行testcaseTest模块的getImportFieldTest方法，参数是'stepDesc', '1. 打开登录页面\n2. 输入用户名密码', $case3 属性steps @1. 打开登录页面\n2. 输入用户名密码
- 执行testcaseTest模块的getImportFieldTest方法，参数是'stepExpect', '1. 页面正常显示\n2. 登录成功', $case4 属性expects @1. 页面正常显示\n2. 登录成功
- 执行testcaseTest模块的getImportFieldTest方法，参数是'stage', "功能测试阶段\n集成测试阶段", $case5 
 - 属性stage @feature
- 执行testcaseTest模块的getImportFieldTest方法，参数是'type', '功能测试', $case6 属性type @feature
- 执行testcaseTest模块的getImportFieldTest方法，参数是'title', '测试用例标题', $case7 属性title @测试用例标题

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

su('admin');

$testcaseTest = new testcaseTest();

// 准备测试用例对象
$case1 = new stdclass();
$case2 = new stdclass();
$case3 = new stdclass();
$case4 = new stdclass();
$case5 = new stdclass();
$case6 = new stdclass();
$case7 = new stdclass();

r($testcaseTest->getImportFieldTest('story', '用户登录功能(#123)', $case1)) && p('story') && e('123');
r($testcaseTest->getImportFieldTest('module', '系统管理模块(#456)', $case2)) && p('module') && e('456');
r($testcaseTest->getImportFieldTest('stepDesc', '1. 打开登录页面\n2. 输入用户名密码', $case3)) && p('steps') && e('1. 打开登录页面\n2. 输入用户名密码');
r($testcaseTest->getImportFieldTest('stepExpect', '1. 页面正常显示\n2. 登录成功', $case4)) && p('expects') && e('1. 页面正常显示\n2. 登录成功');
r($testcaseTest->getImportFieldTest('stage', "功能测试阶段\n集成测试阶段", $case5)) && p('stage') && e('feature,intergrate');
r($testcaseTest->getImportFieldTest('type', '功能测试', $case6)) && p('type') && e('feature');
r($testcaseTest->getImportFieldTest('title', '测试用例标题', $case7)) && p('title') && e('测试用例标题');