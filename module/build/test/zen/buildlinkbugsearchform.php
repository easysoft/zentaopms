#!/usr/bin/env php
<?php

/**

title=测试 buildZen::buildLinkBugSearchForm();
timeout=0
cid=0

- 执行buildTest模块的buildLinkBugSearchFormTest方法，参数是$build1, 1, 'normal' 
 - 属性hasProductField @0
 - 属性hasProjectField @0
 - 属性hasBranchField @0
 - 属性actionURL @/build-view-1-bug-true.html
- 执行buildTest模块的buildLinkBugSearchFormTest方法，参数是$build2, 2, 'branch' 
 - 属性hasProductField @0
 - 属性hasProjectField @0
 - 属性hasBranchField @1
 - 属性branchValues @4
- 执行buildTest模块的buildLinkBugSearchFormTest方法，参数是$build3, 3, 'normal' 
 - 属性hasProductField @0
 - 属性hasProjectField @0
 - 属性hasPlanField @0
 - 属性queryID @3
- 执行buildTest模块的buildLinkBugSearchFormTest方法，参数是$build4, 4, 'platform' 
 - 属性hasProductField @0
 - 属性hasProjectField @0
 - 属性hasBranchField @1
 - 属性style @simple
- 执行buildTest模块的buildLinkBugSearchFormTest方法，参数是$build5, 0, 'normal' 
 - 属性hasProductField @0
 - 属性hasProjectField @0
 - 属性queryID @0
 - 属性planValues @2
 - 属性moduleValues @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('build');
$table->id->range('1-10');
$table->product->range('1-3');
$table->project->range('11-13');
$table->execution->range('101-103');
$table->name->range('Build001,Build002,Build003{2},Build004{3}');
$table->branch->range('1,2,1,2,1,2,3,4,5,6');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$buildTest = new buildTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 测试步骤1：普通产品类型的Bug搜索表单配置
$build1 = new stdclass();
$build1->id = 1;
$build1->product = 1;
$build1->project = 11;
$build1->execution = 101;
$build1->branch = '1';
r($buildTest->buildLinkBugSearchFormTest($build1, 1, 'normal')) && p('hasProductField,hasProjectField,hasBranchField,actionURL') && e('0,0,0,/build-view-1-bug-true.html');

// 测试步骤2：多分支产品类型的Bug搜索表单配置
$build2 = new stdclass();
$build2->id = 2;
$build2->product = 2;
$build2->project = 12;
$build2->execution = 102;
$build2->branch = '1,2';
r($buildTest->buildLinkBugSearchFormTest($build2, 2, 'branch')) && p('hasProductField,hasProjectField,hasBranchField,branchValues') && e('0,0,1,4');

// 测试步骤3：无项目支持的版本Bug搜索表单配置
$build3 = new stdclass();
$build3->id = 3;
$build3->product = 1;
$build3->project = 14;
$build3->execution = 103;
$build3->branch = '';
r($buildTest->buildLinkBugSearchFormTest($build3, 3, 'normal')) && p('hasProductField,hasProjectField,hasPlanField,queryID') && e('0,0,0,3');

// 测试步骤4：多分支版本的Bug搜索表单配置
$build4 = new stdclass();
$build4->id = 4;
$build4->product = 3;
$build4->project = 13;
$build4->execution = 101;
$build4->branch = '1,2,3';
r($buildTest->buildLinkBugSearchFormTest($build4, 4, 'platform')) && p('hasProductField,hasProjectField,hasBranchField,style') && e('0,0,1,simple');

// 测试步骤5：空queryID的Bug搜索表单配置
$build5 = new stdclass();
$build5->id = 5;
$build5->product = 2;
$build5->project = 12;
$build5->execution = 102;
$build5->branch = '0';
r($buildTest->buildLinkBugSearchFormTest($build5, 0, 'normal')) && p('hasProductField,hasProjectField,queryID,planValues,moduleValues') && e('0,0,0,2,2');