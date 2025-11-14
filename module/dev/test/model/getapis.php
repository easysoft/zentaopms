#!/usr/bin/env php
<?php

/**

title=测试 devModel::getAPIs();
timeout=0
cid=16001

- 测试API扩展路径功能属性hasExtPaths @1
- 测试common模块特殊处理 @array
- 测试dev模块特殊处理 @array
- 测试API方法参数解析功能属性hasParams @1
- 测试API扩展路径数量属性extPathCount @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

su('admin');

$devTest = new devTest();

r($devTest->getAPIsExtensionTest()) && p('hasExtPaths') && e('1');             // 测试API扩展路径功能
r($devTest->getAPIsTest('common')) && p() && e('array');                      // 测试common模块特殊处理
r($devTest->getAPIsTest('dev')) && p() && e('array');                         // 测试dev模块特殊处理
r($devTest->getAPIsParameterTest('user')) && p('hasParams') && e('1');        // 测试API方法参数解析功能
r($devTest->getAPIsExtensionTest()) && p('extPathCount') && e('1');            // 测试API扩展路径数量