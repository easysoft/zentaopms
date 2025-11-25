#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::setMenu();
timeout=0
cid=19110

- 步骤1：project标签测试
 - 属性projectID @1
 - 属性executionID @2
 - 属性appTab @project
 - 属性tabChecked @project
- 步骤2：execution标签测试
 - 属性projectID @5
 - 属性executionID @6
 - 属性appTab @execution
 - 属性tabChecked @execution
- 步骤3：qa标签测试
 - 属性projectID @10
 - 属性executionID @11
 - 属性appTab @qa
 - 属性tabChecked @qa
- 步骤4：无标签测试
 - 属性projectID @20
 - 属性executionID @21
 - 属性tabChecked @none
- 步骤5：其他标签测试
 - 属性projectID @0
 - 属性executionID @0
 - 属性appTab @other
 - 属性tabChecked @none

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->setMenuTest(1, 2, 3, 'all', 'project')) && p('projectID,executionID,appTab,tabChecked') && e('1,2,project,project'); // 步骤1：project标签测试
r($testcaseTest->setMenuTest(5, 6, 7, 'main', 'execution')) && p('projectID,executionID,appTab,tabChecked') && e('5,6,execution,execution'); // 步骤2：execution标签测试
r($testcaseTest->setMenuTest(10, 11, 12, 0, 'qa')) && p('projectID,executionID,appTab,tabChecked') && e('10,11,qa,qa'); // 步骤3：qa标签测试
r($testcaseTest->setMenuTest(20, 21, 22, '', '')) && p('projectID,executionID,tabChecked') && e('20,21,none'); // 步骤4：无标签测试
r($testcaseTest->setMenuTest(0, 0, 0, 'all', 'other')) && p('projectID,executionID,appTab,tabChecked') && e('0,0,other,none'); // 步骤5：其他标签测试