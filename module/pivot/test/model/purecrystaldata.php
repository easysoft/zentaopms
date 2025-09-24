#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::pureCrystalData();
timeout=0
cid=0

- 执行pivotTest模块的pureCrystalDataTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivotTest = new pivotTest();

// 测试步骤1：正常数据处理 - 测试包含columns和groups的标准数据结构
r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 100, 'label' => 'Test Data')
            )
        ),
        'groups' => array('group1' => 'value1', 'group2' => 'value2')
    )
))) && p('0:group1,group2,col1:value,col1:label') && e('value1,value2,100,Test Data');

// 测试步骤2：空数组边界处理 - 测试空输入边界情况
r($pivotTest->pureCrystalDataTest(array())) && p() && e('0');

// 测试步骤3：rowTotal字段处理 - 测试rowTotal字段正确添加到cellData
r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 150, 'count' => 5),
                'rowTotal' => 300
            )
        ),
        'groups' => array('category' => 'totals')
    )
))) && p('0:category,col1:value,col1:count,col1:total') && e('totals,150,5,300');

// 测试步骤4：cellData切片数据处理 - 测试切片数据拆分为独立字段
r($pivotTest->pureCrystalDataTest(array(
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
))) && p('0:slice_group,col1_slice1:value,col1_slice1:type,col1_slice2:value,col1_slice2:type') && e('sliced,10,A,20,B');

// 测试步骤5：多记录数据处理 - 测试多条记录的正确处理
r($pivotTest->pureCrystalDataTest(array(
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
))) && p('1:record,col2:value,col2:status') && e('second,300,active');

// 测试步骤6：null值和空值处理 - 测试null值和空cellData的处理
r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => null)
            ),
            'col2' => array(
                'cellData' => array()
            )
        ),
        'groups' => array('null_group' => null, 'empty_group' => 'test')
    )
))) && p('0:null_group,empty_group,col1:value') && e('~~,test,~~');

// 测试步骤7：复杂嵌套切片数据 - 测试多层嵌套的切片数据处理
r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array(
                    'slice1' => array('value' => 50, 'nested' => array('deep' => 'data')),
                    'slice2' => array('count' => 25, 'items' => array(1, 2, 3))
                )
            )
        ),
        'groups' => array('complex' => 'nested')
    )
))) && p('0:complex') && e('nested');

// 测试步骤8：边界值数据类型测试 - 测试不同数据类型的值处理
r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 0, 'bool_true' => true, 'bool_false' => false, 'string_empty' => '')
            )
        ),
        'groups' => array('boundary' => 'types')
    )
))) && p('0:boundary,col1:value,col1:bool_true,col1:bool_false,col1:string_empty') && e('types,0,1,0,~~');