#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::prepareEditExtras();
timeout=0
cid=0

- 步骤1：正常情况 @success_id_1
- 步骤2：步骤验证失败 @validation_failed
- 步骤3：版本号检查 @success_version_2
- 步骤4：自动化脚本 @auto_script_&lt;script&gt;test&lt;/script&gt;
- 步骤5：库案例特殊处理 @lib_case_3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->id->range('1-5');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$case->product->range('1{3},0{2}');
$case->version->range('1,2,3,1,2');
$case->lastEditedBy->range('admin{5}');
$case->status->range('normal{3},wait{2}');
$case->lib->range('0{3},1{2}');
$case->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->prepareEditExtrasTest(1, 1, 'normal', array('期望1'), array('步骤1'), 0, 'no', '', '')) && p() && e('success_id_1'); // 步骤1：正常情况
r($testcaseTest->prepareEditExtrasTest(2, 1, 'normal', array('期望1'), array(''), 0, 'no', '', '')) && p() && e('validation_failed'); // 步骤2：步骤验证失败
r($testcaseTest->prepareEditExtrasTest(3, 2, 'normal', array('期望1'), array('步骤1'), 0, 'no', '', '')) && p() && e('success_version_2'); // 步骤3：版本号检查
r($testcaseTest->prepareEditExtrasTest(4, 1, 'normal', array('期望1'), array('步骤1'), 0, 'auto', '<script>test</script>', '')) && p() && e('auto_script_&lt;script&gt;test&lt;/script&gt;'); // 步骤4：自动化脚本

$_POST['lib'] = 3;
r($testcaseTest->prepareEditExtrasTest(5, 1, 'normal', array('期望1'), array('步骤1'), 0, 'no', '', '')) && p() && e('lib_case_3'); // 步骤5：库案例特殊处理