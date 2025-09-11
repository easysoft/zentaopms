#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processBuildinFieldData();
timeout=0
cid=0

- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'story', $data1, $object1, $relations1 属性custom_field_1 @test_value
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'story', $data2, $object2, $relations2 属性existing_field @existing_value
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'bug', $data3, $object3, $relations3 属性existing_field @existing_value
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'task', $data4, $object4, $relations4 
 - 属性field1 @value1
 - 属性field2 @value2
 - 属性field3 @value3
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'epic', $data5, $object5, $relations5 属性filled_field @filled_value

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

// 测试步骤1：正常字段映射处理
$data1 = new stdclass();
$data1->issuetype = 'Story';
$data1->customfield_10001 = 'test_value';

$object1 = new stdclass();

$relations1 = array(
    'zentaoFieldStory' => array(
        'customfield_10001' => 'custom_field_1'
    )
);

r($convertTest->processBuildinFieldDataTest('story', $data1, $object1, $relations1)) && p('custom_field_1') && e('test_value');

// 测试步骤2：空关系数组处理
$data2 = new stdclass();
$data2->issuetype = 'Story';
$object2 = new stdclass();
$object2->existing_field = 'existing_value';

$relations2 = array();

r($convertTest->processBuildinFieldDataTest('story', $data2, $object2, $relations2)) && p('existing_field') && e('existing_value');

// 测试步骤3：无匹配字段处理
$data3 = new stdclass();
$data3->issuetype = 'Bug';
$data3->unknown_field = 'unknown_value';

$object3 = new stdclass();
$object3->existing_field = 'existing_value';

$relations3 = array(
    'zentaoFieldBug' => array(
        'different_field' => 'mapped_field'
    )
);

r($convertTest->processBuildinFieldDataTest('bug', $data3, $object3, $relations3)) && p('existing_field') && e('existing_value');

// 测试步骤4：多字段同时映射
$data4 = new stdclass();
$data4->issuetype = 'Task';
$data4->customfield_001 = 'value1';
$data4->customfield_002 = 'value2';
$data4->customfield_003 = 'value3';

$object4 = new stdclass();

$relations4 = array(
    'zentaoFieldTask' => array(
        'customfield_001' => 'field1',
        'customfield_002' => 'field2',
        'customfield_003' => 'field3'
    )
);

r($convertTest->processBuildinFieldDataTest('task', $data4, $object4, $relations4)) && p('field1,field2,field3') && e('value1,value2,value3');

// 测试步骤5：空数据字段处理
$data5 = new stdclass();
$data5->issuetype = 'Epic';
$data5->customfield_empty = '';
$data5->customfield_filled = 'filled_value';

$object5 = new stdclass();

$relations5 = array(
    'zentaoFieldEpic' => array(
        'customfield_empty' => 'empty_field',
        'customfield_filled' => 'filled_field'
    )
);

r($convertTest->processBuildinFieldDataTest('epic', $data5, $object5, $relations5)) && p('filled_field') && e('filled_value');