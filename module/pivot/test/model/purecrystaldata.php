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

r($pivotTest->pureCrystalDataTest(array())) && p() && e('0');

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 100)
            )
        ),
        'groups' => array('group1' => 'value1', 'group2' => 'value2')
    )
))) && p('0:group1,group2,col1:value') && e('value1,value2,100');

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 150),
                'rowTotal' => 300
            )
        ),
        'groups' => array('group1' => 'test')
    )
))) && p('0:col1:total') && e('300');

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array(
                    'slice1' => array('value' => 10),
                    'slice2' => array('value' => 20)
                )
            )
        ),
        'groups' => array('group1' => 'complex')
    )
))) && p('0:col1_slice1:value,col1_slice2:value') && e('10,20');

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 100)
            )
        ),
        'groups' => array('group1' => 'record1')
    ),
    1 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 200)
            )
        ),
        'groups' => array('group1' => 'record2')
    )
))) && p('1:group1,col1:value') && e('record2,200');

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array(
                    'slice1' => array('value' => 10, 'label' => 'Label1'),
                    'slice2' => array('value' => 20, 'label' => 'Label2')
                ),
                'rowTotal' => 30
            )
        ),
        'groups' => array('category' => 'nested')
    )
))) && p('0:col1_slice1:label,col1:total') && e('Label1,30');

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array('value' => 100)
            ),
            'col2' => array(
                'cellData' => array(
                    'part1' => array('value' => 50),
                    'part2' => array('value' => 60)
                ),
                'rowTotal' => 110
            ),
            'col3' => array(
                'cellData' => array('value' => 200)
            )
        ),
        'groups' => array('type' => 'comprehensive', 'status' => 'active')
    )
))) && p('0:type,col1:value,col2_part1:value,col2:total,col3:value') && e('comprehensive,100,50,110,200');

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array()
            )
        ),
        'groups' => array('empty' => 'test')
    )
))) && p('0:empty') && e('test');

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array(
                    'slice1' => array('label' => 'NoValue'),
                    'slice2' => array('count' => 5)
                )
            )
        ),
        'groups' => array('missing' => 'value')
    )
))) && p('0:col1_slice1:label,col1_slice2:count') && e('NoValue,5');

r($pivotTest->pureCrystalDataTest(array_fill(0, 50, array(
    'columns' => array(
        'metric' => array(
            'cellData' => array('value' => 10)
        )
    ),
    'groups' => array('batch' => 'data')
)))) && p() && e('50');