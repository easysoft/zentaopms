#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processQueryFilterDefaults();
timeout=0
cid=17425

- 验证字段名第0条的field属性 @status
- 期望返回空数组 @0
- 期望直接返回false @0
- 验证字段保持原状第0条的field属性 @name
- 验证字段保持不变第0条的field属性 @priority

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 包含有效multipleselect过滤器和默认值的数组
$normalFilters = array(
    array(
        'field' => 'status',
        'from' => 'query',
        'type' => 'multipleselect',
        'typeOption' => 'status',
        'default' => array('active', 'inactive', 'invalid_option')
    )
);
r($pivotTest->processQueryFilterDefaultsTest($normalFilters)) && p('0:field') && e('status'); // 验证字段名

// 步骤2：边界值 - 空数组
r($pivotTest->processQueryFilterDefaultsTest(array())) && p() && e('0'); // 期望返回空数组

// 步骤3：异常输入 - filters为false
r($pivotTest->processQueryFilterDefaultsTest(false)) && p() && e('0'); // 期望直接返回false

// 步骤4：业务规则验证 - 包含不符合条件的过滤器
$mixedFilters = array(
    array(
        'field' => 'name',
        'from' => 'form',  // 不符合from='query'条件
        'type' => 'multipleselect',
        'typeOption' => 'name',
        'default' => array('test')
    )
);
r($pivotTest->processQueryFilterDefaultsTest($mixedFilters)) && p('0:field') && e('name'); // 验证字段保持原状

// 步骤5：数据验证 - 包含空默认值的过滤器
$emptyDefaultFilters = array(
    array(
        'field' => 'priority',
        'from' => 'query',
        'type' => 'multipleselect',
        'typeOption' => 'priority',
        'default' => array() // 空默认值
    )
);
r($pivotTest->processQueryFilterDefaultsTest($emptyDefaultFilters)) && p('0:field') && e('priority'); // 验证字段保持不变