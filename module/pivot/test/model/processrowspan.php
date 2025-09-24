#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processRowSpan();
timeout=0
cid=0

- 步骤5：边界条件测试：空records数组输入，返回空数组 @0

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
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan') && e('2,2,1'); // 步骤1：验证相同分组值(A,A,B)合并rowSpan为(2,2,1)

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
), array('group1', 'group2'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan,0:group2:rowSpan,1:group2:rowSpan,2:group2:rowSpan') && e('2,2,1,1,1,1'); // 步骤2：多级分组(A-X,A-Y,B-X)处理，level1合并为(2,2,1)，level2保持(1,1,1)

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
), array('group1'))) && p('0:group1:rowSpan,0:col1:rowSpan,0:col2:rowSpan,1:group1:rowSpan,1:col1:rowSpan,1:col2:rowSpan') && e('3,1,3,2,2,1'); // 步骤3：数组长度影响rowSpan：col1数组[3元素]使rowSpan=3，col2数组[2元素]使rowSpan=2

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
), array('group1'))) && p('0:group1:rowSpan,1:group1:rowSpan,2:group1:rowSpan,3:group1:rowSpan') && e('1,1,2,2'); // 步骤4：$total$标记不参与分组合并，各自rowSpan=1；Normal值正常合并rowSpan=2

r($pivotTest->processRowSpanTest(array(), array('group1'))) && p() && e('0'); // 步骤5：边界条件测试：空records数组输入，返回空数组

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
), array())) && p('0:group1:rowSpan,1:group1:rowSpan,0:col1:rowSpan,1:col1:rowSpan') && e('1,1,1,1'); // 步骤6：空分组数组测试：无分组字段时，所有rowSpan均为1

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'Test'),
        'col1' => array('value' => array('a', 'b')),
        'col2' => array('value' => 'scalar'),
        'col3' => array('value' => null)
    )
), array('group1'))) && p('0:group1:rowSpan,0:col1:rowSpan,0:col2:rowSpan,0:col3:rowSpan') && e('2,1,2,2'); // 步骤7：混合数据类型测试：数组、标量、null值的rowSpan处理正确性

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
), array('level1', 'level2'))) && p('0:level1:rowSpan,1:level1:rowSpan,2:level1:rowSpan,0:data:rowSpan,1:data:rowSpan,2:data:rowSpan') && e('7,7,1,1,1,1'); // 步骤8：大数组处理：5元素和2元素数组，最大长度5作为整体rowSpan基准，level1合并为7

r($pivotTest->processRowSpanTest(array(
    array(
        'group1' => array('value' => 'Test'),
        'col1' => array('value' => array('x', 'y', 'z', 'a', 'b', 'c', 'd', 'e')),
        'col2' => array('value' => array(1, 2, 3)),
        'col3' => array('value' => 'single_value'),
        'col4' => array('value' => array('alpha', 'beta'))
    )
), array('group1'))) && p('0:group1:rowSpan,0:col1:rowSpan,0:col2:rowSpan,0:col3:rowSpan,0:col4:rowSpan') && e('8,1,1,8,1'); // 步骤9：单记录多列测试：8元素数组决定整体rowSpan=8，各列按数据类型设置rowSpan

r($pivotTest->processRowSpanTest(array(
    array(
        'level1' => array('value' => 'Top'),
        'level2' => array('value' => 'Mid'),
        'level3' => array('value' => 'Sub1'),
        'data' => array('value' => 100)
    ),
    array(
        'level1' => array('value' => 'Top'),
        'level2' => array('value' => 'Mid'),
        'level3' => array('value' => 'Sub2'),
        'data' => array('value' => 200)
    ),
    array(
        'level1' => array('value' => 'Top'),
        'level2' => array('value' => 'Other'),
        'level3' => array('value' => 'Sub3'),
        'data' => array('value' => 300)
    ),
    array(
        'level1' => array('value' => 'Bottom'),
        'level2' => array('value' => 'Final'),
        'level3' => array('value' => 'Last'),
        'data' => array('value' => 400)
    )
), array('level1', 'level2', 'level3'))) && p('0:level1:rowSpan,1:level1:rowSpan,2:level1:rowSpan,3:level1:rowSpan,0:level2:rowSpan,1:level2:rowSpan,2:level2:rowSpan,3:level2:rowSpan,0:level3:rowSpan,1:level3:rowSpan,2:level3:rowSpan,3:level3:rowSpan') && e('3,3,3,1,2,2,1,1,1,1,1,1'); // 步骤10：三级分组测试：Top-Mid组合前2行rowSpan=2，Top-Other第3行rowSpan=1，Bottom独立rowSpan=1