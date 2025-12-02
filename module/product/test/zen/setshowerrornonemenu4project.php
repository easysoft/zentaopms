#!/usr/bin/env php
<?php

/**

title=测试 productZen::setShowErrorNoneMenu4Project();
timeout=0
cid=17616

- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'bug', 1
 - 属性projectSuccess @1
 - 属性rawModuleMatch @1
 - 属性shouldSetSubModule @1
- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'testcase', 2
 - 属性projectSuccess @1
 - 属性rawModuleMatch @1
 - 属性shouldSetSubModule @1
- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'testtask', 3
 - 属性projectSuccess @1
 - 属性rawModuleMatch @1
 - 属性shouldSetSubModule @1
- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'testreport', 4
 - 属性projectSuccess @1
 - 属性rawModuleMatch @1
 - 属性shouldSetSubModule @1
- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'projectrelease', 5
 - 属性projectSuccess @1
 - 属性rawModuleMatch @1
 - 属性shouldSetSubModule @1
- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'other', 6 属性projectSuccess @1
- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'', 0
 - 属性projectSuccess @1
 - 属性rawModuleMatch @1
- 执行productTest模块的setShowErrorNoneMenu4ProjectTest方法，参数是'bug', 100
 - 属性projectSuccess @1
 - 属性rawModuleMatch @1
 - 属性shouldSetSubModule @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->setShowErrorNoneMenu4ProjectTest('bug', 1)) && p('projectSuccess,rawModuleMatch,shouldSetSubModule') && e('1,1,1');
r($productTest->setShowErrorNoneMenu4ProjectTest('testcase', 2)) && p('projectSuccess,rawModuleMatch,shouldSetSubModule') && e('1,1,1');
r($productTest->setShowErrorNoneMenu4ProjectTest('testtask', 3)) && p('projectSuccess,rawModuleMatch,shouldSetSubModule') && e('1,1,1');
r($productTest->setShowErrorNoneMenu4ProjectTest('testreport', 4)) && p('projectSuccess,rawModuleMatch,shouldSetSubModule') && e('1,1,1');
r($productTest->setShowErrorNoneMenu4ProjectTest('projectrelease', 5)) && p('projectSuccess,rawModuleMatch,shouldSetSubModule') && e('1,1,1');
r($productTest->setShowErrorNoneMenu4ProjectTest('other', 6)) && p('projectSuccess') && e('1');
r($productTest->setShowErrorNoneMenu4ProjectTest('', 0)) && p('projectSuccess,rawModuleMatch') && e('1,1');
r($productTest->setShowErrorNoneMenu4ProjectTest('bug', 100)) && p('projectSuccess,rawModuleMatch,shouldSetSubModule') && e('1,1,1');