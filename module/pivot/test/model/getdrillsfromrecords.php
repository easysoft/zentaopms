#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getDrillsFromRecords();
timeout=0
cid=17381

- 执行$result1['bug']['drillFields']['status']['id'] @1
- 执行$result2 @0
- 执行$result3['story']['drillFields'] @0
- 执行$result4['']['drillFields']['name']['field'] @1
- 执行$result5 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivotTest = new pivotTest();

// 测试步骤1：正常输入情况，包含drillFields的records和有效groups
$records1 = array(
    array(
        'category' => 'bug',
        'status' => array(
            'value' => 'active',
            'drillFields' => array('id' => 1, 'title' => 'Bug Status')
        )
    )
);
$groups1 = array('category');
$result1 = $pivotTest->getDrillsFromRecordsTest($records1, $groups1);
r(isset($result1['bug']['drillFields']['status']['id'])) && p() && e('1');

// 测试步骤2：边界值输入，空records数组
$records2 = array();
$groups2 = array('category');
$result2 = $pivotTest->getDrillsFromRecordsTest($records2, $groups2);
r(count($result2)) && p() && e('0');

// 测试步骤3：无效输入情况，records中没有drillFields
$records3 = array(
    array(
        'category' => 'story',
        'status' => 'active'
    )
);
$groups3 = array('category');
$result3 = $pivotTest->getDrillsFromRecordsTest($records3, $groups3);
r(count($result3['story']['drillFields'])) && p() && e('0');

// 测试步骤4：groups为空数组的情况
$records4 = array(
    array(
        'name' => array(
            'value' => 'test',
            'drillFields' => array('field' => 'name', 'operator' => '=')
        )
    )
);
$groups4 = array();
$result4 = $pivotTest->getDrillsFromRecordsTest($records4, $groups4);
r(isset($result4['']['drillFields']['name']['field'])) && p() && e('1');

// 测试步骤5：复杂业务场景，多个groups和多个drillFields
$records5 = array(
    array(
        'category' => 'bug',
        'priority' => '1',
        'status' => array(
            'value' => 'active',
            'drillFields' => array('id' => 1, 'status' => 'active')
        ),
        'assignedTo' => array(
            'value' => 'user1',
            'drillFields' => array('user' => 'user1', 'dept' => 'dev')
        )
    ),
    array(
        'category' => 'bug',
        'priority' => '2',
        'status' => array(
            'value' => 'resolved',
            'drillFields' => array('id' => 2, 'status' => 'resolved')
        )
    )
);
$groups5 = array('category', 'priority');
$result5 = $pivotTest->getDrillsFromRecordsTest($records5, $groups5);
r(count($result5)) && p() && e('2');