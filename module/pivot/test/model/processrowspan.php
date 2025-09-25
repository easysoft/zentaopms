#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processRowSpan();
timeout=0
cid=0

- 步骤5：空数组边界测试 @0

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
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan') && e('2,2,1'); // 步骤1：基础分组合并测试

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
), array('group1', 'group2'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan,0:group2:rowSpan,1:group2:rowSpan,2:group2:rowSpan') && e('2,2,1,1,1,1'); // 步骤2：多级分组处理测试

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
), array('group1'))) && p('0:group1:rowSpan,0:col1:rowSpan,0:col2:rowSpan,1:group1:rowSpan,1:col1:rowSpan,1:col2:rowSpan') && e('3,1,3,2,2,1'); // 步骤3：数组值影响rowSpan测试

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
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan,3:group1:rowSpan') && e('1,1,2,2'); // 步骤4：特殊标记处理测试

r($pivotTest->processRowSpanTest(array(), array('group1'))) && p() && e('0'); // 步骤5：空数组边界测试

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
), array())) && p('0:group1:rowSpan,1:group1:rowSpan,0:col1:rowSpan,1:col1:rowSpan') && e('1,1,1,1'); // 步骤6：空分组参数测试

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'Test'),
        'col1' => array('value' => array('a', 'b')),
        'col2' => array('value' => 'scalar'),
        'col3' => array('value' => null)
    )
), array('group1'))) && p('0:group1:rowSpan,0:col1:rowSpan,0:col2:rowSpan,0:col3:rowSpan') && e('2,1,2,2'); // 步骤7：混合数据类型测试

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
), array('level1', 'level2'))) && p('0:level1:rowSpan,1:level1:rowSpan,2:level1:rowSpan,0:data:rowSpan,1:data:rowSpan,2:data:rowSpan') && e('7,7,1,1,1,1'); // 步骤8：复杂数组场景测试

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => ''),
        'col1' => array('value' => 10)
    ),
    array(
        'group1' => array('value' => ''),
        'col1' => array('value' => 20)
    ),
    array(
        'group1' => array('value' => 'Different'),
        'col1' => array('value' => 30)
    )
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan,0:col1:rowSpan,1:col1:rowSpan,2:col1:rowSpan') && e('2,2,1,1,1,1'); // 步骤9：空字符串分组值测试

r($pivotTest->processRowSpanTest(array(
    array(
        'category' => array('value' => 'A'),
        'type' => array('value' => 'T1'),
        'subtype' => array('value' => 'S1'),
        'data' => array('value' => 10)
    ),
    array(
        'category' => array('value' => 'A'),
        'type' => array('value' => 'T1'),
        'subtype' => array('value' => 'S2'),
        'data' => array('value' => 20)
    ),
    array(
        'category' => array('value' => 'A'),
        'type' => array('value' => 'T2'),
        'subtype' => array('value' => 'S1'),
        'data' => array('value' => 30)
    ),
    array(
        'category' => array('value' => 'B'),
        'type' => array('value' => 'T1'),
        'subtype' => array('value' => 'S1'),
        'data' => array('value' => 40)
    )
), array('category', 'type', 'subtype'))) && p('0:category:rowSpan,1:category:rowSpan,2:category:rowSpan,3:category:rowSpan,0:type:rowSpan,1:type:rowSpan,2:type:rowSpan,3:type:rowSpan') && e('3,3,3,1,2,2,1,1'); // 步骤10：三级嵌套分组复杂场景测试