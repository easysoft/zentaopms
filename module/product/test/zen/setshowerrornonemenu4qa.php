#!/usr/bin/env php
<?php

/**

title=测试 productZen::setShowErrorNoneMenu4QA();
timeout=0
cid=0

- 测试步骤1:使用testcase作为activeMenu
 - 属性success @1
 - 属性rawModuleMatch @1
 - 属性shouldUnsetSubMenu @1
- 测试步骤2:使用testsuite作为activeMenu
 - 属性success @1
 - 属性rawModuleMatch @1
 - 属性shouldUnsetSubMenu @1
- 测试步骤3:使用testtask作为activeMenu
 - 属性success @1
 - 属性rawModuleMatch @1
 - 属性shouldUnsetSubMenu @1
- 测试步骤4:使用testreport作为activeMenu
 - 属性success @1
 - 属性rawModuleMatch @1
 - 属性shouldUnsetSubMenu @1
- 测试步骤5:使用空字符串作为activeMenu
 - 属性success @1
 - 属性rawModuleMatch @1
- 测试步骤6:使用bug作为activeMenu(不需要取消子菜单)
 - 属性success @1
 - 属性rawModuleMatch @1
 - 属性shouldUnsetSubMenu @0
- 测试步骤7:使用未知菜单作为activeMenu
 - 属性success @1
 - 属性rawModuleMatch @1
 - 属性shouldUnsetSubMenu @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->setShowErrorNoneMenu4QATest('testcase')) && p('success,rawModuleMatch,shouldUnsetSubMenu') && e('1,1,1'); // 测试步骤1:使用testcase作为activeMenu
r($productTest->setShowErrorNoneMenu4QATest('testsuite')) && p('success,rawModuleMatch,shouldUnsetSubMenu') && e('1,1,1'); // 测试步骤2:使用testsuite作为activeMenu
r($productTest->setShowErrorNoneMenu4QATest('testtask')) && p('success,rawModuleMatch,shouldUnsetSubMenu') && e('1,1,1'); // 测试步骤3:使用testtask作为activeMenu
r($productTest->setShowErrorNoneMenu4QATest('testreport')) && p('success,rawModuleMatch,shouldUnsetSubMenu') && e('1,1,1'); // 测试步骤4:使用testreport作为activeMenu
r($productTest->setShowErrorNoneMenu4QATest('')) && p('success,rawModuleMatch') && e('1,1'); // 测试步骤5:使用空字符串作为activeMenu
r($productTest->setShowErrorNoneMenu4QATest('bug')) && p('success,rawModuleMatch,shouldUnsetSubMenu') && e('1,1,0'); // 测试步骤6:使用bug作为activeMenu(不需要取消子菜单)
r($productTest->setShowErrorNoneMenu4QATest('unknown')) && p('success,rawModuleMatch,shouldUnsetSubMenu') && e('1,1,0'); // 测试步骤7:使用未知菜单作为activeMenu