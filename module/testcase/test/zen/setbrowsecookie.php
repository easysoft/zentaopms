#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::setBrowseCookie();
timeout=0
cid=0

- 步骤1：正常设置产品ID和分支属性preProductID @1
- 步骤2：设置browseType为bymodule并指定模块ID属性caseModule @10
- 步骤3：设置browseType为bysuite并指定套件ID属性caseSuite @5
- 步骤4：设置不同的产品ID触发caseModule重置属性caseModule @0
- 步骤5：设置不同的分支触发caseModule重置属性caseModule @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseZenTest = new testcaseZenTest();

r($testcaseZenTest->setBrowseCookieTest(1, 'branch1', '', '')) && p('preProductID') && e('1'); // 步骤1：正常设置产品ID和分支
r($testcaseZenTest->setBrowseCookieTest(1, 'branch1', 'bymodule', '10')) && p('caseModule') && e('10'); // 步骤2：设置browseType为bymodule并指定模块ID
r($testcaseZenTest->setBrowseCookieTest(1, 'branch1', 'bysuite', '5')) && p('caseSuite') && e('5'); // 步骤3：设置browseType为bysuite并指定套件ID
r($testcaseZenTest->setBrowseCookieTest(3, 'branch1', '', '')) && p('caseModule') && e('0'); // 步骤4：设置不同的产品ID触发caseModule重置
r($testcaseZenTest->setBrowseCookieTest(2, 'branch2', '', '')) && p('caseModule') && e('0'); // 步骤5：设置不同的分支触发caseModule重置