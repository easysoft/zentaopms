#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::flattenCrystalData();
timeout=0
cid=17366

- 执行pivotTest模块的flattenCrystalDataTest方法，参数是$simpleData  @3
- 执行pivotTest模块的flattenCrystalDataTest方法，参数是$groupData, false  @3
- 执行pivotTest模块的flattenCrystalDataTest方法，参数是$groupDataWithSummary, true  @5
- 执行pivotTest模块的flattenCrystalDataTest方法，参数是$emptyData  @0
- 执行pivotTest模块的flattenCrystalDataTest方法，参数是$nestedGroupData, false  @4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：正常情况 - 测试简单行数据（无分组结构）
$simpleData = array(
    array('name' => 'John', 'age' => 25, 'department' => 'IT'),
    array('name' => 'Jane', 'age' => 30, 'department' => 'HR'),
    array('name' => 'Bob', 'age' => 35, 'department' => 'Finance')
);
r(count($pivotTest->flattenCrystalDataTest($simpleData))) && p() && e('3');

// 步骤2：边界值 - 测试包含分组结构的数据（不包含汇总行）
$groupData = array(
    array(
        'rows' => array(
            array('name' => 'John', 'age' => 25, 'department' => 'IT'),
            array('name' => 'Alice', 'age' => 28, 'department' => 'IT')
        )
    ),
    array(
        'rows' => array(
            array('name' => 'Jane', 'age' => 30, 'department' => 'HR')
        )
    )
);
r(count($pivotTest->flattenCrystalDataTest($groupData, false))) && p() && e('3');

// 步骤3：异常输入 - 测试包含分组结构的数据（包含汇总行）
$groupDataWithSummary = array(
    array(
        'rows' => array(
            array('name' => 'John', 'age' => 25, 'department' => 'IT'),
            array('name' => 'Alice', 'age' => 28, 'department' => 'IT')
        ),
        'summary' => array('total' => 2, 'avg_age' => 26.5, 'department' => 'IT')
    ),
    array(
        'rows' => array(
            array('name' => 'Jane', 'age' => 30, 'department' => 'HR')
        ),
        'summary' => array('total' => 1, 'avg_age' => 30, 'department' => 'HR')
    )
);
r(count($pivotTest->flattenCrystalDataTest($groupDataWithSummary, true))) && p() && e('5');

// 步骤4：权限验证 - 测试边界值（空数组输入）
$emptyData = array();
r(count($pivotTest->flattenCrystalDataTest($emptyData))) && p() && e('0');

// 步骤5：业务规则 - 测试复杂嵌套分组结构数据
$nestedGroupData = array(
    array(
        'rows' => array(
            array(
                'rows' => array(
                    array('name' => 'John', 'age' => 25, 'department' => 'IT', 'level' => 'Senior'),
                    array('name' => 'Alice', 'age' => 28, 'department' => 'IT', 'level' => 'Senior')
                )
            ),
            array(
                'rows' => array(
                    array('name' => 'Mike', 'age' => 22, 'department' => 'IT', 'level' => 'Junior')
                )
            )
        )
    ),
    array(
        'rows' => array(
            array('name' => 'Jane', 'age' => 30, 'department' => 'HR', 'level' => 'Manager')
        )
    )
);
r(count($pivotTest->flattenCrystalDataTest($nestedGroupData, false))) && p() && e('4');