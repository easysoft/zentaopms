#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processBuildinFieldData();
timeout=0
cid=15868

- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'story', $data1, $object1, $relations1 
 - 属性custom_field_1 @test_value
 - 属性custom_field_2 @another_value
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'story', $data2, $object2, $relations2 属性existing_field @existing_value
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'bug', $data3, $object3, $relations3 属性existing_field @existing_value
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'task', $data4, $object4, $relations4 
 - 属性field1 @value1
 - 属性field2 @value2
 - 属性field3 @value3
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'epic', $data5, $object5, $relations5 
 - 属性filled_field @filled_value
 - 属性existing_field @keep_this
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'story', $data6, $object6, $relations6 
 - 属性mapped_valid @valid_data
 - 属性mapped_another @another_data
 - 属性pre_existing @original_value
- 执行convertTest模块的processBuildinFieldDataTest方法，参数是'testcase', $data7, $object7, $relations7 
 - 属性zentao_field_1 @test_value_1
 - 属性zentao_field_2 @test_value_2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

// 测试步骤1：基本字段映射功能
$data1 = new stdclass();
$data1->issuetype = 'Story';
$data1->customfield_10001 = 'test_value';
$data1->customfield_10002 = 'another_value';

$object1 = new stdclass();

$relations1 = array(
    'zentaoFieldStory' => array(
        'customfield_10001' => 'custom_field_1',
        'customfield_10002' => 'custom_field_2'
    )
);

r($convertTest->processBuildinFieldDataTest('story', $data1, $object1, $relations1)) && p('custom_field_1,custom_field_2') && e('test_value,another_value');

// 测试步骤2：空关系数组处理
$data2 = new stdclass();
$data2->issuetype = 'Story';
$data2->some_field = 'some_value';

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

// 测试步骤5：空字段值处理
$data5 = new stdclass();
$data5->issuetype = 'Epic';
$data5->customfield_empty = '';
$data5->customfield_filled = 'filled_value';

$object5 = new stdclass();
$object5->existing_field = 'keep_this';

$relations5 = array(
    'zentaoFieldEpic' => array(
        'customfield_empty' => 'empty_field',
        'customfield_filled' => 'filled_field'
    )
);

r($convertTest->processBuildinFieldDataTest('epic', $data5, $object5, $relations5)) && p('filled_field,existing_field') && e('filled_value,keep_this');

// 测试步骤6：复杂映射场景
$data6 = new stdclass();
$data6->issuetype = 'Story';
$data6->valid_field = 'valid_data';
$data6->empty_field = '';
$data6->another_valid = 'another_data';

$object6 = new stdclass();
$object6->pre_existing = 'original_value';

$relations6 = array(
    'zentaoFieldStory' => array(
        'valid_field' => 'mapped_valid',
        'empty_field' => 'mapped_empty',
        'another_valid' => 'mapped_another',
        'non_existing' => 'mapped_none'
    )
);

r($convertTest->processBuildinFieldDataTest('story', $data6, $object6, $relations6)) && p('mapped_valid,mapped_another,pre_existing') && e('valid_data,another_data,original_value');

// 测试步骤7：不同模块的字段映射
$data7 = new stdclass();
$data7->issuetype = 'TestCase';
$data7->test_field_1 = 'test_value_1';
$data7->test_field_2 = 'test_value_2';

$object7 = new stdclass();

$relations7 = array(
    'zentaoFieldTestCase' => array(
        'test_field_1' => 'zentao_field_1',
        'test_field_2' => 'zentao_field_2'
    )
);

r($convertTest->processBuildinFieldDataTest('testcase', $data7, $object7, $relations7)) && p('zentao_field_1,zentao_field_2') && e('test_value_1,test_value_2');