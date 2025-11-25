#!/usr/bin/env php
<?php

/**

title=测试 compileZen::buildSearchForm();
timeout=0
cid=15760

- 步骤1：测试无参数情况，验证repo字段存在属性hasRepoField @1
- 步骤2：测试repoID参数为1，验证repo字段不存在属性hasRepoField @0
- 步骤3：测试jobID参数为1，验证repo字段不存在属性hasRepoField @0
- 步骤4：测试queryID参数为5，验证queryID被设置属性queryID @5
- 步骤5：测试所有参数设置，验证repo字段不存在属性hasRepoField @0
- 步骤6：测试repoID和jobID同时设置，验证repo字段不存在属性hasRepoField @0
- 步骤7：测试大queryID值，验证queryID被正确设置属性queryID @100

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备
$repo = zenData('repo');
$repo->id->range('1-10');
$repo->name->range('Repository1,Repository2,Repository3');
$repo->deleted->range('0');
$repo->gen(3);

$job = zenData('job');
$job->id->range('1-20');
$job->name->range('Job1,Job2,Job3');
$job->deleted->range('0');
$job->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 3.5. 初始化compile配置
global $tester;
$tester->loadModel('compile');

// 4. 创建测试实例（变量名与模块名一致）
$compileTest = new compileZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($compileTest->buildSearchFormTest(0, 0, 0)) && p('hasRepoField') && e('1'); // 步骤1：测试无参数情况，验证repo字段存在
r($compileTest->buildSearchFormTest(1, 0, 0)) && p('hasRepoField') && e('0'); // 步骤2：测试repoID参数为1，验证repo字段不存在
r($compileTest->buildSearchFormTest(0, 1, 0)) && p('hasRepoField') && e('0'); // 步骤3：测试jobID参数为1，验证repo字段不存在
r($compileTest->buildSearchFormTest(0, 0, 5)) && p('queryID') && e('5'); // 步骤4：测试queryID参数为5，验证queryID被设置
r($compileTest->buildSearchFormTest(1, 1, 10)) && p('hasRepoField') && e('0'); // 步骤5：测试所有参数设置，验证repo字段不存在
r($compileTest->buildSearchFormTest(2, 3, 0)) && p('hasRepoField') && e('0'); // 步骤6：测试repoID和jobID同时设置，验证repo字段不存在
r($compileTest->buildSearchFormTest(0, 0, 100)) && p('queryID') && e('100'); // 步骤7：测试大queryID值，验证queryID被正确设置