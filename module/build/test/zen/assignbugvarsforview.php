#!/usr/bin/env php
<?php

/**

title=测试 buildZen::assignBugVarsForView();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性type @bug
 - 属性bugCount @3
- 步骤2：Bug列表处理属性param @1
- 步骤3：执行Bug数据属性generatedBugCount @3
- 步骤4：空Bug数据属性bugCount @0
- 步骤5：边界情况
 - 属性bugCount @5
 - 属性generatedBugCount @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$build = zenData('build');
$build->loadYaml('build_assignbugvarsforview', false, 2)->gen(10);

$bug = zenData('bug');
$bug->loadYaml('bug_assignbugvarsforview', false, 2)->gen(200);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$buildTest = new buildTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 创建分页对象模拟
class mockPager {
    public $recTotal = 0;
    public $recPerPage = 20;
    public $pageTotal = 1;
    public $pageID = 1;
}
$bugPager = new mockPager();
$generatedBugPager = new mockPager();

// 创建测试版本对象
$build1 = new stdclass();
$build1->id = 1;
$build1->name = 'Build1';
$build1->project = 1;
$build1->execution = 1;
$build1->product = 1;
$build1->branch = '0';
$build1->allBugs = '1,2,3';
$build1->builds = '1,2';

r($buildTest->assignBugVarsForViewTest($build1, 'bug', 'id_desc', '0', $bugPager, $generatedBugPager)) && p('type,bugCount') && e('bug,3'); // 步骤1：正常情况
r($buildTest->assignBugVarsForViewTest($build1, 'bug', 'status_desc', '1', $bugPager, $generatedBugPager)) && p('param') && e('1'); // 步骤2：Bug列表处理
r($buildTest->assignBugVarsForViewTest($build1, 'generatedBug', 'id_desc', '0', $bugPager, $generatedBugPager)) && p('generatedBugCount') && e('3'); // 步骤3：执行Bug数据

// 测试空版本数据
$build2 = new stdclass();
$build2->id = 2;
$build2->name = 'EmptyBuild';
$build2->project = 2;
$build2->execution = 0;
$build2->product = 2;
$build2->branch = '0';
$build2->allBugs = '';
$build2->builds = '';

r($buildTest->assignBugVarsForViewTest($build2, 'bug', 'id_desc', '0', $bugPager, $generatedBugPager)) && p('bugCount') && e('0'); // 步骤4：空Bug数据

// 测试边界条件
$build3 = new stdclass();
$build3->id = 3;
$build3->name = 'LargeBuild';
$build3->project = 3;
$build3->execution = 3;
$build3->product = 3;
$build3->branch = '1,2';
$build3->allBugs = '10,11,12,13,14';
$build3->builds = '10,11,12';

r($buildTest->assignBugVarsForViewTest($build3, 'bug', 'severity_desc', '2', $bugPager, $generatedBugPager)) && p('bugCount,generatedBugCount') && e('5,3'); // 步骤5：边界情况