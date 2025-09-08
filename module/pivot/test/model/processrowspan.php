#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processRowSpan();
timeout=0
cid=0

- 步骤3：空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'A'),
        'col1' => array('value' => 100),
        'col2' => array('value' => 200)
    ),
    array(
        'group1' => array('value' => 'B'),
        'col1' => array('value' => 150),
        'col2' => array('value' => 250)
    )
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan') && e('1,1'); // 步骤1：正常情况

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'A'),
        'group2' => array('value' => 'X'),
        'col1' => array('value' => 100)
    ),
    array(
        'group1' => array('value' => 'A'),
        'group2' => array('value' => 'Y'),
        'col1' => array('value' => 150)
    ),
    array(
        'group1' => array('value' => 'B'),
        'group2' => array('value' => 'X'),
        'col1' => array('value' => 200)
    )
), array('group1', 'group2'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan') && e('2,2,1'); // 步骤2：多组分组

r($pivotTest->processRowSpanTest(array(), array('group1'))) && p() && e('0'); // 步骤3：空数组

r($pivotTest->processRowSpanTest(array(
    array(
        'col1' => array('value' => 100),
        'col2' => array('value' => 200)
    ),
    array(
        'col1' => array('value' => 150),
        'col2' => array('value' => 250)
    )
), array())) && p('0:col1:rowSpan,1:col1:rowSpan') && e('1,1'); // 步骤4：无分组

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'A'),
        'col1' => array('value' => array(100, 150, 200)),
        'col2' => array('value' => 300)
    ),
    array(
        'group1' => array('value' => 'A'),
        'col1' => array('value' => 400),
        'col2' => array('value' => 500)
    )
), array('group1'))) && p('0:col1:rowSpan,0:col2:rowSpan,1:group1:rowSpan') && e('3,3,6'); // 步骤5：数组值处理