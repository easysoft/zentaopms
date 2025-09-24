#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processRowSpan();
timeout=0
cid=0

- 步骤5：边界情况空数组处理 @0

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
        'group1' => array('value' => 'A'),
        'col1' => array('value' => 150),
        'col2' => array('value' => 250)
    ),
    array(
        'group1' => array('value' => 'B'),
        'col1' => array('value' => 300),
        'col2' => array('value' => 350)
    )
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan') && e('2,2,1'); // 步骤1：基础单级分组rowSpan计算

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
), array('group1', 'group2'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan,0:group2:rowSpan,1:group2:rowSpan,2:group2:rowSpan') && e('2,2,1,1,1,1'); // 步骤2：多级分组层次化rowSpan处理

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'A'),
        'col1' => array('value' => array(100, 150, 200)),
        'col2' => array('value' => 300)
    ),
    array(
        'group1' => array('value' => 'B'),
        'col1' => array('value' => 400),
        'col2' => array('value' => array(500, 600))
    )
), array('group1'))) && p('0:group1:rowSpan,0:col1:rowSpan,0:col2:rowSpan,1:group1:rowSpan,1:col1:rowSpan,1:col2:rowSpan') && e('3,1,3,2,2,1'); // 步骤3：数组值影响rowSpan计算

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => '$total$'),
        'col1' => array('value' => 100)
    ),
    array(
        'group1' => array('value' => '$total$'),
        'col1' => array('value' => 150)
    ),
    array(
        'group1' => array('value' => 'Normal'),
        'col1' => array('value' => 200)
    ),
    array(
        'group1' => array('value' => 'Normal'),
        'col1' => array('value' => 250)
    )
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan,3:group1:rowSpan') && e('1,1,2,2'); // 步骤4：特殊值$total$处理逻辑

r($pivotTest->processRowSpanTest(array(), array('group1'))) && p() && e('0'); // 步骤5：边界情况空数组处理

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'Same'),
        'group2' => array('value' => 'A'),
        'col1' => array('value' => 100)
    ),
    array(
        'group1' => array('value' => 'Same'),
        'group2' => array('value' => 'B'),
        'col1' => array('value' => 200)
    )
), array())) && p('0:group1:rowSpan,1:group1:rowSpan,0:col1:rowSpan,1:col1:rowSpan') && e('1,1,1,1'); // 步骤6：空分组字段数组处理

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'Test'),
        'col1' => array('value' => array('a', 'b')),
        'col2' => array('value' => 'scalar'),
        'col3' => array('value' => null)
    )
), array('group1'))) && p('0:group1:rowSpan,0:col1:rowSpan,0:col2:rowSpan,0:col3:rowSpan') && e('2,1,2,2'); // 步骤7：数据完整性验证

r($pivotTest->processRowSpanTest(array(
    array(
        'level1' => array('value' => 'A'),
        'level2' => array('value' => 'X'),
        'data' => array('value' => array(1, 2, 3, 4, 5))
    ),
    array(
        'level1' => array('value' => 'A'),
        'level2' => array('value' => 'Y'),
        'data' => array('value' => array('alpha', 'beta'))
    ),
    array(
        'level1' => array('value' => 'B'),
        'level2' => array('value' => 'Z'),
        'data' => array('value' => 'single')
    )
), array('level1', 'level2'))) && p('0:level1:rowSpan,1:level1:rowSpan,2:level1:rowSpan,0:data:rowSpan,1:data:rowSpan,2:data:rowSpan') && e('7,7,1,1,1,1'); // 步骤8：复杂嵌套数据结构处理