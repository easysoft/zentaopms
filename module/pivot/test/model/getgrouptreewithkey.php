#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getGroupTreeWithKey();
timeout=0
cid=17390

- 执行pivotTest模块的getGroupTreeWithKeyTest方法，参数是$data1  @key1
- 执行pivotTest模块的getGroupTreeWithKeyTest方法，参数是$data2 属性group1 @item1
- 执行pivotTest模块的getGroupTreeWithKeyTest方法，参数是$data3 第group1条的subgroup1属性 @item1
- 执行pivotTest模块的getGroupTreeWithKeyTest方法，参数是$data4  @single
- 执行pivotTest模块的getGroupTreeWithKeyTest方法，参数是$data5 属性group2 @level1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：测试无groups字段的数据 - 期望返回groupKey
$data1 = array(
    array('groupKey' => 'key1', 'value' => 'data1'),
    array('groupKey' => 'key2', 'value' => 'data2')
);
r($pivotTest->getGroupTreeWithKeyTest($data1)) && p() && e('key1');

// 步骤2：测试单层分组数据 - 期望构建基本树形结构
$data2 = array(
    array('groups' => array('group1'), 'groupKey' => 'item1', 'value' => 'data1'),
    array('groups' => array('group1'), 'groupKey' => 'item2', 'value' => 'data2'),
    array('groups' => array('group2'), 'groupKey' => 'item3', 'value' => 'data3')
);
r($pivotTest->getGroupTreeWithKeyTest($data2)) && p('group1') && e('item1');

// 步骤3：测试多层递归分组数据 - 期望正确递归处理
$data3 = array(
    array('groups' => array('group1', 'subgroup1'), 'groupKey' => 'item1', 'value' => 'data1'),
    array('groups' => array('group1', 'subgroup2'), 'groupKey' => 'item2', 'value' => 'data2'),
    array('groups' => array('group2', 'subgroup1'), 'groupKey' => 'item3', 'value' => 'data3')
);
r($pivotTest->getGroupTreeWithKeyTest($data3)) && p('group1:subgroup1') && e('item1');

// 步骤4：测试单个元素无groups字段 - 期望处理边界情况  
$data4 = array(
    array('groupKey' => 'single', 'value' => 'data')
);
r($pivotTest->getGroupTreeWithKeyTest($data4)) && p() && e('single');

// 步骤5：测试混合数据结构 - 期望正确处理复杂场景
$data5 = array(
    array('groups' => array('group1', 'subgroup1', 'level3'), 'groupKey' => 'deep1', 'value' => 'data1'),
    array('groups' => array('group1', 'subgroup2'), 'groupKey' => 'level2', 'value' => 'data2'),
    array('groups' => array('group2'), 'groupKey' => 'level1', 'value' => 'data3')
);
r($pivotTest->getGroupTreeWithKeyTest($data5)) && p('group2') && e('level1');