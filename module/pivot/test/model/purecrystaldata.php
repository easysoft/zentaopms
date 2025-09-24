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

r($pivotTest->pureCrystalDataTest(array())) && p() && e('0');

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

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 100, 'label' => 'Column 1')
            ),
            'col2' => array(
                'cellData' => array('value' => 200, 'label' => 'Column 2'),
                'rowTotal' => 500
            )
        ),
        'groups' => array('multi_col' => 'test')
    )
))) && p('0:multi_col,col1:value,col1:label,col2:value,col2:label,col2:total') && e('test,100,Column 1,200,Column 2,500');

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array()
            )
        ),
        'groups' => array('empty_test' => 'empty')
    )
))) && p('0:empty_test') && e('empty');

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array(
                    'slice1' => array('value' => 10, 'count' => 2, 'rate' => 0.5),
                    'slice2' => array('value' => 20, 'count' => 3, 'rate' => 0.6),
                    'slice3' => array('value' => 30, 'count' => 1, 'rate' => 0.8)
                ),
                'rowTotal' => 60
            )
        ),
        'groups' => array('complex' => 'multi_slice', 'type' => 'analysis')
    )
))) && p('0:complex,type,col1_slice1:value,col1_slice1:count,col1_slice2:rate,col1_slice3:value') && e('multi_slice,analysis,10,2,0.6,30');