#!/usr/bin/env php
<?php

/**

title=测试 dataviewModel::getModuleNames();
timeout=0
cid=15954

- 步骤1：正常表名转换测试
 - 属性zt_bug @bug
 - 属性zt_project @project
- 步骤2：空数组输入测试 @0
- 步骤3：非zt_前缀表名过滤测试属性zt_task @task
- 步骤4：特殊模块名转换测试
 - 属性zt_case @testcase
 - 属性zt_module @tree
- 步骤5：无效模块名过滤测试属性zt_user @user

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$dataviewTest = new dataviewModelTest();

r($dataviewTest->getModuleNamesTest(array('zt_bug', 'zt_project'))) && p('zt_bug,zt_project') && e('bug,project'); // 步骤1：正常表名转换测试
r($dataviewTest->getModuleNamesTest(array())) && p() && e('0'); // 步骤2：空数组输入测试
r($dataviewTest->getModuleNamesTest(array('bug', 'project', 'zt_task'))) && p('zt_task') && e('task'); // 步骤3：非zt_前缀表名过滤测试
r($dataviewTest->getModuleNamesTest(array('zt_case', 'zt_module'))) && p('zt_case,zt_module') && e('testcase,tree'); // 步骤4：特殊模块名转换测试
r($dataviewTest->getModuleNamesTest(array('zt_bug123', 'zt_test_case', 'zt_user'))) && p('zt_user') && e('user'); // 步骤5：无效模块名过滤测试