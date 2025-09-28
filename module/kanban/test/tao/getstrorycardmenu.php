#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getStoryCardMenu();
timeout=0
cid=0

- 步骤1：正常情况返回菜单数组 @5
- 步骤2：空需求数组返回空数组 @0
- 步骤3：无产品权限情况返回菜单数组 @2
- 步骤4：草稿状态需求返回菜单数组 @1
- 步骤5：已关闭状态需求返回菜单数组 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('story')->loadYaml('story_getstrorycardmenu', true, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->getStoryCardMenuTest('normalCase')) && p() && e('5'); // 步骤1：正常情况返回菜单数组
r($kanbanTest->getStoryCardMenuTest('emptyArray')) && p() && e('0'); // 步骤2：空需求数组返回空数组
r($kanbanTest->getStoryCardMenuTest('noProductPermission')) && p() && e('2'); // 步骤3：无产品权限情况返回菜单数组
r($kanbanTest->getStoryCardMenuTest('draftStatus')) && p() && e('1'); // 步骤4：草稿状态需求返回菜单数组
r($kanbanTest->getStoryCardMenuTest('closedStatus')) && p() && e('1'); // 步骤5：已关闭状态需求返回菜单数组