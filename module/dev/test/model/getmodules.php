#!/usr/bin/env php
<?php

/**

title=测试 devModel::getModules();
timeout=0
cid=16007

- 执行devTest模块的getModulesStructureTest方法
 - 属性hasGroups @1
 - 属性validStructure @1
- 执行devTest模块的getModulesTest方法 属性admin @action
- 执行devTest模块的getModulesTest方法 属性product @branch
- 执行devTest模块的getModulesExcludeTest方法
 - 属性common @0
 - 属性editor @0
 - 属性help @0
 - 属性setting @0
- 执行devTest模块的getModulesWithExtensionTest方法  @95

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

su('admin');

$devTest = new devTest();

r($devTest->getModulesStructureTest()) && p('hasGroups,validStructure') && e('1,1');
r($devTest->getModulesTest()) && p('admin') && e('action');
r($devTest->getModulesTest()) && p('product') && e('branch');
r($devTest->getModulesExcludeTest()) && p('common,editor,help,setting') && e('0,0,0,0');
r($devTest->getModulesWithExtensionTest()) && p() && e('95');