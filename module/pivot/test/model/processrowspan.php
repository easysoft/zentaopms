#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processRowSpan();
timeout=0
cid=0

- 步骤6：边界值测试-空数组 @0

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
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan,0:col1:rowSpan,1:col1:rowSpan') && e('1,1,1,1'); // 步骤1：基本单级分组功能测试

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
), array('group1', 'group2'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan,0:group2:rowSpan,1:group2:rowSpan,2:group2:rowSpan') && e('2,2,1,1,1,1'); // 步骤2：多级分组层次测试

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'A'),
        'col1' => array('value' => array(100, 150, 200)),
        'col2' => array('value' => 300)
    ),
    array(
        'group1' => array('value' => 'B'),
        'col1' => array('value' => 400),
        'col2' => array('value' => 500)
    )
), array('group1'))) && p('0:col1:rowSpan,0:col2:rowSpan,1:col1:rowSpan,1:col2:rowSpan') && e('1,3,3,3'); // 步骤3：数组值的rowSpan计算测试

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'A'),
        'col1' => array('value' => 100)
    ),
    array(
        'group1' => array('value' => 'A'),
        'col1' => array('value' => 150)
    ),
    array(
        'group1' => array('value' => 'A'),
        'col1' => array('value' => 200)
    )
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan') && e('3,3,3'); // 步骤4：相同分组值合并测试

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => '$total$'),
        'col1' => array('value' => 100)
    ),
    array(
        'group1' => array('value' => '$total$'),
        'col1' => array('value' => 150)
    )
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan') && e('1,1'); // 步骤5：特殊值$total$处理测试

r($pivotTest->processRowSpanTest(array(), array('group1'))) && p() && e('0'); // 步骤6：边界值测试-空数组

r($pivotTest->processRowSpanTest(array(
    array(
        'col1' => array('value' => 100),
        'col2' => array('value' => 200)
    ),
    array(
        'col1' => array('value' => 150),
        'col2' => array('value' => 250)
    )
), array())) && p('0:col1:rowSpan,1:col1:rowSpan,0:col2:rowSpan,1:col2:rowSpan') && e('1,1,1,1'); // 步骤7：边界值测试-空分组

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'A'),
        'col1' => array('value' => array(1, 2)),
        'col2' => array('value' => array(3, 4, 5, 6)),
        'col3' => array('value' => 'scalar')
    ),
    array(
        'group1' => array('value' => 'A'),
        'col1' => array('value' => array(7, 8, 9)),
        'col2' => array('value' => 'single'),
        'col3' => array('value' => array(10))
    )
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan,0:col1:rowSpan,0:col2:rowSpan,0:col3:rowSpan,1:col1:rowSpan,1:col2:rowSpan,1:col3:rowSpan') && e('8,8,1,4,4,1,3,3'); // 步骤8：复杂嵌套数组rowSpan计算