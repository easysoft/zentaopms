#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::getMenuItems();
timeout=0
cid=0

- 步骤1：正常情况 @2
- 步骤2：边界值 @0
- 步骤3：异常输入 @1
- 步骤4：权限验证 @0
- 步骤5：业务规则 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
// 步骤1：正常情况 - 包含url的菜单项
$normalMenus = array();
$menu1 = new stdClass();
$menu1->id = 1;
$menu1->name = 'Test Menu 1';
$menu1->url = 'http://example.com/menu1';
$normalMenus[] = $menu1;

$menu2 = new stdClass();
$menu2->id = 2;
$menu2->name = 'Test Menu 2';
$menu2->url = 'http://example.com/menu2';
$normalMenus[] = $menu2;

r($pivotTest->getMenuItemsCountTest($normalMenus)) && p() && e('2'); // 步骤1：正常情况

// 步骤2：边界值 - 空数组输入
$emptyMenus = array();
r($pivotTest->getMenuItemsCountTest($emptyMenus)) && p() && e('0'); // 步骤2：边界值

// 步骤3：混合情况 - 包含url和不包含url的菜单项
$mixedMenus = array();
$menuWithUrl = new stdClass();
$menuWithUrl->id = 3;
$menuWithUrl->name = 'With URL';
$menuWithUrl->url = 'http://example.com/with-url';
$mixedMenus[] = $menuWithUrl;

$menuWithoutUrl = new stdClass();
$menuWithoutUrl->id = 4;
$menuWithoutUrl->name = 'Without URL';
$mixedMenus[] = $menuWithoutUrl;

r($pivotTest->getMenuItemsCountTest($mixedMenus)) && p() && e('1'); // 步骤3：异常输入

// 步骤4：权限验证 - 所有菜单项都没有url属性
$noUrlMenus = array();
$menuNoUrl1 = new stdClass();
$menuNoUrl1->id = 5;
$menuNoUrl1->name = 'No URL 1';
$noUrlMenus[] = $menuNoUrl1;

$menuNoUrl2 = new stdClass();
$menuNoUrl2->id = 6;
$menuNoUrl2->name = 'No URL 2';
$noUrlMenus[] = $menuNoUrl2;

r($pivotTest->getMenuItemsCountTest($noUrlMenus)) && p() && e('0'); // 步骤4：权限验证

// 步骤5：业务规则 - 包含复杂菜单对象结构
$complexMenus = array();
$complexMenu1 = new stdClass();
$complexMenu1->id = 7;
$complexMenu1->name = 'Complex Menu 1';
$complexMenu1->url = 'http://example.com/complex1';
$complexMenu1->parent = 0;
$complexMenu1->extra = 'extra_data';
$complexMenus[] = $complexMenu1;

$complexMenu2 = new stdClass();
$complexMenu2->id = 8;
$complexMenu2->name = 'Complex Menu 2';
$complexMenu2->parent = 7;
$complexMenus[] = $complexMenu2; // 没有url

$complexMenu3 = new stdClass();
$complexMenu3->id = 9;
$complexMenu3->name = 'Complex Menu 3';
$complexMenu3->url = 'http://example.com/complex3';
$complexMenus[] = $complexMenu3;

r($pivotTest->getMenuItemsCountTest($complexMenus)) && p() && e('2'); // 步骤5：业务规则