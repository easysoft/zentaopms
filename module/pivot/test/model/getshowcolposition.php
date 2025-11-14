#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getShowColPosition();
timeout=0
cid=17401

- 步骤1：columnTotal为noShow @noShow
- 步骤2：columnTotal为show且columnPosition为bottom @bottom
- 步骤3：columnTotal为show且columnPosition为row @row
- 步骤4：columnTotal为show且columnPosition为all @all
- 步骤5：columnTotal为show且columnPosition未设置 @bottom

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->getShowColPositionTest(array('columnTotal' => 'noShow', 'columnPosition' => 'bottom'))) && p() && e('noShow'); // 步骤1：columnTotal为noShow
r($pivotTest->getShowColPositionTest(array('columnTotal' => 'show', 'columnPosition' => 'bottom'))) && p() && e('bottom'); // 步骤2：columnTotal为show且columnPosition为bottom
r($pivotTest->getShowColPositionTest(array('columnTotal' => 'show', 'columnPosition' => 'row'))) && p() && e('row'); // 步骤3：columnTotal为show且columnPosition为row
r($pivotTest->getShowColPositionTest(array('columnTotal' => 'show', 'columnPosition' => 'all'))) && p() && e('all'); // 步骤4：columnTotal为show且columnPosition为all
r($pivotTest->getShowColPositionTest(array('columnTotal' => 'show'))) && p() && e('bottom'); // 步骤5：columnTotal为show且columnPosition未设置