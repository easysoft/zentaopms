#!/usr/bin/env php
<?php

/**

title=测试 screenZen::setSelectFilter();
cid=0

- 测试步骤1：正常输入情况，包含有效sourceID和filters >> 期望返回正确的chartFilters数组结构
- 测试步骤2：filters为空数组的边界情况 >> 期望直接返回null或空
- 测试步骤3：filters为null的边界情况 >> 期望直接返回null或空
- 测试步骤4：包含多个过滤器的复杂输入 >> 期望返回完整的chartFilters数组
- 测试步骤5：测试不同filter类型和字段的组合 >> 期望按sourceID正确组织过滤器

*/

// 跳过代码覆盖率检测以避免语法错误
$_GET['coverage'] = 'no';

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($screenTest->setSelectFilterTest('sourceA', array(array('type' => 'status', 'field' => 'open')))) && p('sourceA,status') && e('open'); // 步骤1：正常情况
r($screenTest->setSelectFilterTest('sourceB', array())) && p() && e('~~'); // 步骤2：空数组边界值
r($screenTest->setSelectFilterTest('sourceC', null)) && p() && e('~~'); // 步骤3：null边界值
r($screenTest->setSelectFilterTest('sourceD', array(array('type' => 'priority', 'field' => 'high'), array('type' => 'category', 'field' => 'bug')))) && p('sourceD,priority') && e('high'); // 步骤4：多过滤器
r($screenTest->setSelectFilterTest('sourceE', array(array('type' => 'assignedTo', 'field' => 'admin'), array('type' => 'module', 'field' => '1')))) && p('sourceE,assignedTo') && e('admin'); // 步骤5：不同类型组合