#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processPercentage();
timeout=0
cid=17423

- 执行$result1['rows'][0]['name'] @test1
- 执行$result2['rows'] @0
- 执行$result3['rows'][0]['rows'][0]['name'] @child1
- 执行$result4['summary']['count'] @10
- 执行$result5['rows'][0]['rows'][0]['rows'][1]['employee'] @Bob

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest = new pivotModelTest();

// 步骤1：正常结构的crystal数据处理
$result1 = $pivotTest->processPercentageTest(
    array(
        'rows' => array(
            array('name' => 'test1', 'value' => 100),
            array('name' => 'test2', 'value' => 200)
        ),
        'summary' => array('total' => 300)
    ),
    array('total' => 500)
);
r($result1['rows'][0]['name']) && p() && e('test1');

// 步骤2：空数据结构处理
$result2 = $pivotTest->processPercentageTest(
    array(
        'rows' => array(),
        'summary' => array()
    ),
    array()
);
r(count($result2['rows'])) && p() && e('0');

// 步骤3：嵌套rows结构的递归处理
$result3 = $pivotTest->processPercentageTest(
    array(
        'rows' => array(
            array(
                'name' => 'group1',
                'rows' => array(
                    array('name' => 'child1', 'value' => 50),
                    array('name' => 'child2', 'value' => 150)
                ),
                'summary' => array('total' => 200)
            )
        ),
        'summary' => array('total' => 200)
    ),
    array('total' => 400)
);
r($result3['rows'][0]['rows'][0]['name']) && p() && e('child1');

// 步骤4：单行数据无嵌套的处理
$result4 = $pivotTest->processPercentageTest(
    array(
        'rows' => array(
            array('category' => 'A', 'count' => 10, 'amount' => 1000)
        ),
        'summary' => array('count' => 10, 'amount' => 1000)
    ),
    array('count' => 50, 'amount' => 5000)
);
r($result4['summary']['count']) && p() && e(10);

// 步骤5：复杂多层次数据结构处理
$result5 = $pivotTest->processPercentageTest(
    array(
        'rows' => array(
            array(
                'department' => 'IT',
                'rows' => array(
                    array(
                        'team' => 'Dev',
                        'rows' => array(
                            array('employee' => 'Alice', 'salary' => 5000),
                            array('employee' => 'Bob', 'salary' => 6000)
                        ),
                        'summary' => array('total_salary' => 11000)
                    ),
                    array('team' => 'QA', 'salary' => 4000)
                ),
                'summary' => array('total_salary' => 15000)
            ),
            array('department' => 'HR', 'salary' => 3000)
        ),
        'summary' => array('total_salary' => 18000)
    ),
    array('total_salary' => 25000)
);
r($result5['rows'][0]['rows'][0]['rows'][1]['employee']) && p() && e('Bob');