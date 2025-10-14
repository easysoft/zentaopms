#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::setBrowseCookie();
timeout=0
cid=0

- 步骤1：正常设置产品ID和分支
 - 属性preProductID @1
 - 属性preBranch @main
- 步骤2：设置不同的产品ID导致caseModule重置属性caseModule @0
- 步骤3：设置不同的分支导致caseModule重置属性caseModule @0
- 步骤4：设置bymodule浏览类型属性caseModule @123
- 步骤5：设置bysuite浏览类型属性caseSuite @456

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($testcaseTest->setBrowseCookieTest(1, 'main')) && p('preProductID,preBranch') && e('1,main'); // 步骤1：正常设置产品ID和分支
r($testcaseTest->setBrowseCookieTest(3, 'main')) && p('caseModule') && e('0'); // 步骤2：设置不同的产品ID导致caseModule重置
r($testcaseTest->setBrowseCookieTest(1, 'develop')) && p('caseModule') && e('0'); // 步骤3：设置不同的分支导致caseModule重置
r($testcaseTest->setBrowseCookieTest(1, 'main', 'bymodule', '123')) && p('caseModule') && e('123'); // 步骤4：设置bymodule浏览类型
r($testcaseTest->setBrowseCookieTest(1, 'main', 'bysuite', '456')) && p('caseSuite') && e('456'); // 步骤5：设置bysuite浏览类型