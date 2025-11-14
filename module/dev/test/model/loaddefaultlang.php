#!/usr/bin/env php
<?php

/**

title=测试 devModel::loadDefaultLang();
timeout=0
cid=16016

- 测试步骤1：默认参数调用检查URCommon属性属性URCommon @$URCOMMON
- 测试步骤2：指定zh-cn语言和common模块检查productCommon属性属性productCommon @$PRODUCTCOMMON
- 测试步骤3：非common模块且未设置defaultLang检查返回值 @0
- 测试步骤4：空语言参数默认为zh-cn检查ERCommon属性属性ERCommon @$ERCOMMON
- 测试步骤5：指定en语言检查SRCommon属性属性SRCommon @$SRCOMMON
- 测试步骤6：设置defaultLang后加载非common模块检查是否返回对象 @1
- 测试步骤7：检查language类型返回值是否正确 @1
- 测试步骤8：检查hourCommon属性继承属性hourCommon @工时

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

global $tester;
$devTest = new devTest();

r($devTest->loadDefaultLangTest()) && p('URCommon') && e('$URCOMMON'); // 测试步骤1：默认参数调用检查URCommon属性
r($devTest->loadDefaultLangTest('zh-cn', 'common')) && p('productCommon') && e('$PRODUCTCOMMON'); // 测试步骤2：指定zh-cn语言和common模块检查productCommon属性
r($devTest->loadDefaultLangTest('zh-cn', 'project')) && p() && e('0'); // 测试步骤3：非common模块且未设置defaultLang检查返回值
r($devTest->loadDefaultLangTest('', 'common')) && p('ERCommon') && e('$ERCOMMON'); // 测试步骤4：空语言参数默认为zh-cn检查ERCommon属性
r($devTest->loadDefaultLangTest('en', 'common')) && p('SRCommon') && e('$SRCOMMON'); // 测试步骤5：指定en语言检查SRCommon属性
r($devTest->loadDefaultLangObjectReturnTest('zh-cn', 'user')) && p() && e('1'); // 测试步骤6：设置defaultLang后加载非common模块检查是否返回对象
r($devTest->loadDefaultLangReturnTypeTest('zh-cn', 'common')) && p() && e('1'); // 测试步骤7：检查language类型返回值是否正确
r($devTest->loadDefaultLangTest('zh-cn', 'common')) && p('hourCommon') && e('工时'); // 测试步骤8：检查hourCommon属性继承