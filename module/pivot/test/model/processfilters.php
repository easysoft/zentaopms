#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processFilters();
timeout=0
cid=17420

- 步骤1：published状态移除design过滤器 @2
- 步骤2：design状态只保留当前用户过滤器 @1
- 步骤3：空数组输入 @0
- 步骤4：其他状态值处理 @3
- 步骤5：混合状态过滤器处理 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 准备测试数据
$publishedFilters = array(
    array('name' => 'filter1', 'status' => 'published', 'account' => 'admin'),
    array('name' => 'filter2', 'status' => 'design', 'account' => 'admin'),
    array('name' => 'filter3', 'status' => 'published', 'account' => 'user1')
);

$designFilters = array(
    array('name' => 'filter1', 'status' => 'design', 'account' => 'admin'),
    array('name' => 'filter2', 'status' => 'design', 'account' => 'user1'),
    array('name' => 'filter3', 'status' => 'published', 'account' => 'admin')
);

$emptyFilters = array();

$mixedFilters = array(
    array('name' => 'filter1', 'account' => 'admin'),
    array('name' => 'filter2', 'status' => 'design', 'account' => 'admin'),
    array('name' => 'filter3', 'status' => 'published', 'account' => 'user1')
);

// 5. 强制要求：必须包含至少5个测试步骤
r(count($pivotTest->processFiltersTest($publishedFilters, 'published'))) && p() && e('2'); // 步骤1：published状态移除design过滤器
r(count($pivotTest->processFiltersTest($designFilters, 'design'))) && p() && e('1'); // 步骤2：design状态只保留当前用户过滤器
r(count($pivotTest->processFiltersTest($emptyFilters, 'published'))) && p() && e('0'); // 步骤3：空数组输入
r(count($pivotTest->processFiltersTest($publishedFilters, 'other'))) && p() && e('3'); // 步骤4：其他状态值处理
r(count($pivotTest->processFiltersTest($mixedFilters, 'published'))) && p() && e('2'); // 步骤5：混合状态过滤器处理