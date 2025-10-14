#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignCasesForBrowse();
timeout=0
cid=0

- 步骤1：正常产品ID和参数获取用例数据属性orderBy @id_desc
- 步骤2：不同browseType参数测试属性orderBy @id_desc
- 步骤3：测试排序参数caseID替换功能属性orderBy @caseID_desc
- 步骤4：测试分页参数功能第pager条的recPerPage属性 @10
- 步骤5：测试from参数不同值的处理属性orderBy @id_desc

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$case = zenData('case');
$case->loadYaml('case_assigncasesforbrowse', false, 2);
$case->gen(15);

$product = zenData('product');
$product->loadYaml('case_assigncasesforbrowse', false, 2);
$product->gen(3);

$story = zenData('story');
$story->loadYaml('case_assigncasesforbrowse', false, 2);
$story->gen(10);

$module = zenData('module');
$module->loadYaml('case_assigncasesforbrowse', false, 2);
$module->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseTest->assignCasesForBrowseTest(1, 'all', 'all', 0, 0, '', 'id_desc', 0, 20, 1, 'testcase')) && p('orderBy') && e('id_desc'); // 步骤1：正常产品ID和参数获取用例数据
r($testcaseTest->assignCasesForBrowseTest(1, 'all', 'wait', 0, 0, '', 'id_desc', 0, 20, 1, 'testcase')) && p('orderBy') && e('id_desc'); // 步骤2：不同browseType参数测试
r($testcaseTest->assignCasesForBrowseTest(1, 'all', 'all', 0, 0, '', 'caseID_desc', 0, 20, 1, 'testcase')) && p('orderBy') && e('caseID_desc'); // 步骤3：测试排序参数caseID替换功能
r($testcaseTest->assignCasesForBrowseTest(1, 'all', 'all', 0, 0, '', 'id_desc', 50, 10, 2, 'testcase')) && p('pager:recPerPage') && e('10'); // 步骤4：测试分页参数功能
r($testcaseTest->assignCasesForBrowseTest(1, 'all', 'all', 0, 0, '', 'id_desc', 0, 20, 1, 'doc')) && p('orderBy') && e('id_desc'); // 步骤5：测试from参数不同值的处理