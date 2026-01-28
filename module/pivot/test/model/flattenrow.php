#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::flattenRow();
timeout=0
cid=17367

- 执行pivotTest模块的flattenRowTest方法，参数是$scalarRow 
 - 第name条的value属性 @admin
 - 第status条的value属性 @active
 - 第count条的value属性 @10
- 执行pivotTest模块的flattenRowTest方法，参数是$mixedRow 
 - 第name条的value属性 @user1
 - 第total条的value属性 @100
 - 第status条的value属性 @active
- 执行pivotTest模块的flattenRowTest方法，参数是$complexRow 
 - 第project条的value属性 @Project A
 - 第progress条的value属性 @75
 - 第simple条的value属性 @test
- 执行pivotTest模块的flattenRowTest方法，参数是$emptyRow  @0
- 执行pivotTest模块的flattenRowTest方法，参数是$emptyValueRow 
 - 第field2条的value属性 @~~
 - 第field3条的value属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest = new pivotModelTest();

// 测试步骤1：纯标量数据行处理
$scalarRow = array(
    'name' => 'admin',
    'status' => 'active',
    'count' => 10
);
r($pivotTest->flattenRowTest($scalarRow)) && p('name:value;status:value;count:value') && e('admin;active;10');

// 测试步骤2：混合数据行处理（标量和带value键数据）
$mixedRow = array(
    'name' => 'user1',
    'total' => array('value' => 100, 'format' => 'number'),
    'status' => 'active'
);
r($pivotTest->flattenRowTest($mixedRow)) && p('name:value;total:value;status:value') && e('user1;100;active');

// 测试步骤3：复杂单元格数据处理（包含额外属性）
$complexRow = array(
    'project' => array('value' => 'Project A', 'color' => 'blue', 'link' => '/project/1'),
    'progress' => array('value' => 75, 'unit' => '%', 'style' => 'progress-bar'),
    'simple' => 'test'
);
r($pivotTest->flattenRowTest($complexRow)) && p('project:value;progress:value;simple:value') && e('Project A;75;test');

// 测试步骤4：空数据行处理
$emptyRow = array();
r($pivotTest->flattenRowTest($emptyRow)) && p() && e(0);

// 测试步骤5：带有空值数据行处理
$emptyValueRow = array(
    'field2' => '',
    'field3' => 0
);
r($pivotTest->flattenRowTest($emptyValueRow)) && p('field2:value;field3:value') && e('~~;0');