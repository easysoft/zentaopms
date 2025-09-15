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
                'cellData' => array('value' => 100)
            )
        ),
        'groups' => array('group1' => 'value1')
    )
))) && p('0:group1') && e('value1');

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
                    'slice1' => array('value' => 10)
                )
            )
        ),
        'groups' => array('group1' => 'complex')
    )
))) && p('0:col1_slice1:value') && e('10');

r($pivotTest->pureCrystalDataTest(array())) && p() && e('0');

r($pivotTest->pureCrystalDataTest(array(
    0 => array(
        'columns' => array(
            'col1' => array(
                'cellData' => array(
                    'part1' => array('value' => 30)
                )
            )
        ),
        'groups' => array('type' => 'multi')
    )
))) && p('0:col1_part1:value') && e('30');