#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignCreateVars();
timeout=0
cid=0

- 步骤1:正常产品ID,空分支,无模块,无来源属性executed @1
- 步骤2:正常产品ID,from为project,param为项目ID属性executed @1
- 步骤3:正常产品ID,from为execution,param为执行ID属性executed @1
- 步骤4:有分支的产品,指定分支属性executed @1
- 步骤5:正常产品ID,指定模块ID和storyID属性executed @1
- 步骤6:from为testcase,param为用例ID属性executed @1
- 步骤7:from为bug,param为bug ID属性executed @1

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
zendata('product')->loadYaml('assigncreatevars/product', false, 2)->gen(10);
zendata('branch')->loadYaml('assigncreatevars/branch', false, 2)->gen(5);
zendata('module')->loadYaml('assigncreatevars/module', false, 2)->gen(10);
zendata('story')->loadYaml('assigncreatevars/story', false, 2)->gen(10);
zendata('case')->loadYaml('assigncreatevars/case', false, 2)->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 5. 测试步骤
r($testcaseTest->assignCreateVarsTest(1, '', 0, '', 0, 0)) && p('executed') && e('1'); // 步骤1:正常产品ID,空分支,无模块,无来源
r($testcaseTest->assignCreateVarsTest(1, '', 0, 'project', 1, 0)) && p('executed') && e('1'); // 步骤2:正常产品ID,from为project,param为项目ID
r($testcaseTest->assignCreateVarsTest(1, '', 0, 'execution', 1, 0)) && p('executed') && e('1'); // 步骤3:正常产品ID,from为execution,param为执行ID
r($testcaseTest->assignCreateVarsTest(6, '1', 0, '', 0, 0)) && p('executed') && e('1'); // 步骤4:有分支的产品,指定分支
r($testcaseTest->assignCreateVarsTest(1, '', 5, '', 0, 1)) && p('executed') && e('1'); // 步骤5:正常产品ID,指定模块ID和storyID
r($testcaseTest->assignCreateVarsTest(1, '', 0, 'testcase', 1, 0)) && p('executed') && e('1'); // 步骤6:from为testcase,param为用例ID
r($testcaseTest->assignCreateVarsTest(1, '', 0, 'bug', 1, 0)) && p('executed') && e('1'); // 步骤7:from为bug,param为bug ID