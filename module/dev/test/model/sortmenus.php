#!/usr/bin/env php
<?php

/**

title=测试 devModel::sortMenus();
timeout=0
cid=16020

- 执行devTester模块的sortMenusTest方法，参数是array  @0
- 执行devTester模块的sortMenusTest方法，参数是$menuWithoutOrder  @home,product,project,

- 执行devTester模块的sortMenusTest方法，参数是$menuWithOrder  @product,home,project,menuOrder,

- 执行devTester模块的sortMenusTest方法，参数是$menuWithProject  @scrum,waterfall,kanbanProject,project,home,menuOrder,

- 执行devTester模块的sortMenusTest方法，参数是$menuObject  @dashboard,report,menuOrder,

- 执行devTester模块的sortMenusTest方法，参数是$complexMenu  @dashboard,epic,story,plan,scrum,waterfall,kanbanProject,project,release,menuOrder,

- 执行devTester模块的sortMenusTest方法，参数是$menuNumericOrder  @a,b,c,menuOrder,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$devTester = new devModelTest();

// 步骤1：测试空数组输入
r($devTester->sortMenusTest(array())) && p() && e('0');

// 步骤2：测试无menuOrder的菜单数组
$menuWithoutOrder = array('home' => 'Home', 'product' => 'Product', 'project' => 'Project');
r($devTester->sortMenusTest($menuWithoutOrder)) && p() && e('home,product,project,');

// 步骤3：测试有menuOrder的普通菜单排序
$menuWithOrder = array(
    'home' => 'Home',
    'product' => 'Product',
    'project' => 'Project',
    'menuOrder' => array(5 => 'product', 10 => 'home', 15 => 'project')
);
r($devTester->sortMenusTest($menuWithOrder)) && p() && e('product,home,project,menuOrder,');

// 步骤4：测试包含project类型的菜单排序和特殊处理
$menuWithProject = array(
    'scrum' => 'Scrum Project',
    'waterfall' => 'Waterfall Project',
    'kanbanProject' => 'Kanban Project',
    'home' => 'Home',
    'project' => 'Project',
    'menuOrder' => array(5 => 'project', 10 => 'home')
);
r($devTester->sortMenusTest($menuWithProject)) && p() && e('scrum,waterfall,kanbanProject,project,home,menuOrder,');

// 步骤5：测试对象类型输入转换
$menuObject = (object)array(
    'dashboard' => 'Dashboard',
    'report' => 'Report',
    'menuOrder' => array(10 => 'report', 5 => 'dashboard')
);
r($devTester->sortMenusTest($menuObject)) && p() && e('dashboard,report,menuOrder,');

// 步骤6：测试复杂菜单混合排序
$complexMenu = array(
    'dashboard' => 'Dashboard',
    'epic' => 'Epic',
    'story' => 'Story',
    'plan' => 'Plan',
    'project' => 'Project',
    'scrum' => 'Scrum',
    'waterfall' => 'Waterfall',
    'kanbanProject' => 'Kanban',
    'release' => 'Release',
    'menuOrder' => array(1 => 'dashboard', 2 => 'epic', 3 => 'story', 4 => 'plan', 5 => 'project', 6 => 'release')
);
r($devTester->sortMenusTest($complexMenu)) && p() && e('dashboard,epic,story,plan,scrum,waterfall,kanbanProject,project,release,menuOrder,');

// 步骤7：测试menuOrder数字键排序
$menuNumericOrder = array(
    'c' => 'Third',
    'a' => 'First',
    'b' => 'Second',
    'menuOrder' => array(30 => 'c', 10 => 'a', 20 => 'b')
);
r($devTester->sortMenusTest($menuNumericOrder)) && p() && e('a,b,c,menuOrder,');