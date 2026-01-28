#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::pureCrystalData();
timeout=0
cid=17428

- 执行pivotTest模块的pureCrystalDataTest方法，参数是$testData1 第0条的group1属性 @value1
- 执行pivotTest模块的pureCrystalDataTest方法，参数是$testData2  @0
- 执行pivotTest模块的pureCrystalDataTest方法，参数是$testData3 第0条的category属性 @totals
- 执行pivotTest模块的pureCrystalDataTest方法，参数是$testData4 第0条的slice_group属性 @sliced
- 执行pivotTest模块的pureCrystalDataTest方法，参数是$testData5 第1条的record属性 @second
- 执行pivotTest模块的pureCrystalDataTest方法，参数是$testData6 第0条的multi_col属性 @test
- 执行pivotTest模块的pureCrystalDataTest方法，参数是$testData7 第0条的empty_test属性 @empty

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$pivotTest = new pivotModelTest();

// 测试步骤1：正常数据处理 - 包含value类型的cellData
$testData1 = array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 100, 'label' => 'Test Data')
            )
        ),
        'groups' => array('group1' => 'value1', 'group2' => 'value2')
    )
);
r($pivotTest->pureCrystalDataTest($testData1)) && p('0:group1') && e('value1');

// 测试步骤2：空数组输入边界值测试
$testData2 = array();
r($pivotTest->pureCrystalDataTest($testData2)) && p() && e('0');

// 测试步骤3：带rowTotal的数据处理
$testData3 = array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 150, 'count' => 5),
                'rowTotal' => 300
            )
        ),
        'groups' => array('category' => 'totals')
    )
);
r($pivotTest->pureCrystalDataTest($testData3)) && p('0:category') && e('totals');

// 测试步骤4：切片数据处理 - cellData不包含value字段
$testData4 = array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array(
                    'slice1' => array('value' => 10, 'type' => 'A'),
                    'slice2' => array('value' => 20, 'type' => 'B')
                )
            )
        ),
        'groups' => array('slice_group' => 'sliced')
    )
);
r($pivotTest->pureCrystalDataTest($testData4)) && p('0:slice_group') && e('sliced');

// 测试步骤5：多条记录数据处理
$testData5 = array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 200, 'flag' => true)
            )
        ),
        'groups' => array('record' => 'first')
    ),
    1 => array(
        'columns' => array(
            'col2' => array(
                'cellData' => array('value' => 300, 'status' => 'active')
            )
        ),
        'groups' => array('record' => 'second')
    )
);
r($pivotTest->pureCrystalDataTest($testData5)) && p('1:record') && e('second');

// 测试步骤6：复杂多列数据处理
$testData6 = array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 100, 'label' => 'Column 1')
            ),
            'col2' => array(
                'cellData' => array('value' => 200, 'label' => 'Column 2'),
                'rowTotal' => 500
            ),
            'col3' => array(
                'cellData' => array(
                    'part1' => array('count' => 15, 'rate' => 0.75),
                    'part2' => array('count' => 5, 'rate' => 0.25)
                )
            )
        ),
        'groups' => array('multi_col' => 'test', 'type' => 'complex')
    )
);
r($pivotTest->pureCrystalDataTest($testData6)) && p('0:multi_col') && e('test');

// 测试步骤7：边界值测试 - 空cellData处理
$testData7 = array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array()
            ),
            'col2' => array(
                'cellData' => array('value' => 0)
            )
        ),
        'groups' => array('empty_test' => 'empty', 'zero_value' => 'zero')
    )
);
r($pivotTest->pureCrystalDataTest($testData7)) && p('0:empty_test') && e('empty');