#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::setConditionValueWithFilters();
timeout=0
cid=17430

- 执行pivotTest模块的setConditionValueWithFiltersTest方法，参数是$condition1, $filters1  @ = 'active'
- 执行pivotTest模块的setConditionValueWithFiltersTest方法，参数是$condition2, $filters2  @0
- 执行pivotTest模块的setConditionValueWithFiltersTest方法，参数是$condition3, $filters3  @0
- 执行pivotTest模块的setConditionValueWithFiltersTest方法，参数是$condition4, $filters4  @ LIKE '%test%'
- 执行pivotTest模块的setConditionValueWithFiltersTest方法，参数是$condition5, $filters5  @ > 2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 字段存在于过滤器中
$condition1 = array('queryField' => 'status');
$filters1 = array(
    'status' => array(
        'operator' => '=',
        'value' => "'active'"
    )
);
r($pivotTest->setConditionValueWithFiltersTest($condition1, $filters1)) && p() && e(" = 'active'");

// 步骤2：边界值 - 字段不存在于过滤器中
$condition2 = array('queryField' => 'nonexistent_field');
$filters2 = array(
    'status' => array(
        'operator' => '=',
        'value' => "'active'"
    )
);
r($pivotTest->setConditionValueWithFiltersTest($condition2, $filters2)) && p() && e('0');

// 步骤3：异常输入 - 空过滤器数组
$condition3 = array('queryField' => 'status');
$filters3 = array();
r($pivotTest->setConditionValueWithFiltersTest($condition3, $filters3)) && p() && e('0');

// 步骤4：复杂操作符 - 使用LIKE操作符
$condition4 = array('queryField' => 'name');
$filters4 = array(
    'name' => array(
        'operator' => 'LIKE',
        'value' => "'%test%'"
    )
);
r($pivotTest->setConditionValueWithFiltersTest($condition4, $filters4)) && p() && e(" LIKE '%test%'");

// 步骤5：数值类型 - 数值类型的值
$condition5 = array('queryField' => 'priority');
$filters5 = array(
    'priority' => array(
        'operator' => '>',
        'value' => '2'
    )
);
r($pivotTest->setConditionValueWithFiltersTest($condition5, $filters5)) && p() && e(' > 2');