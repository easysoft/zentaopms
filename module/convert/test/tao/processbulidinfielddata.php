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
$data1->issuetype = 'story';
$data1->customfield_10001 = 'Mapped Story';
$data1->customfield_10002 = '3';

$object1 = new stdclass();

$relations1 = array(
    'zentaoFieldstory' => array(
        'customfield_10001' => 'title',
        'customfield_10002' => 'pri'
    )
);

r($convertTest->processBuildinFieldDataTest('story', $data1, $object1, $relations1)) && p('title,pri') && e('Mapped Story,3');

// 测试步骤2：空关系数组处理
$data2 = new stdclass();
$data2->issuetype = 'story';
$data2->some_field = 'some_value';

$object2 = new stdclass();
$object2->existing_field = 'existing_value';

$relations2 = array();

r($convertTest->processBuildinFieldDataTest('story', $data2, $object2, $relations2)) && p('existing_field') && e('existing_value');

// 测试步骤3：无匹配字段处理
$data3 = new stdclass();
$data3->issuetype = 'bug';
$data3->unknown_field = 'unknown_value';

$object3 = new stdclass();
$object3->existing_field = 'existing_value';

$relations3 = array(
    'zentaoFieldbug' => array(
        'different_field' => 'mapped_field'
    )
);

r($convertTest->processBuildinFieldDataTest('bug', $data3, $object3, $relations3)) && p('existing_field') && e('existing_value');

// 测试步骤4：多字段同时映射
$data4 = new stdclass();
$data4->issuetype = 'task';
$data4->customfield_001 = 'Task Name';
$data4->customfield_002 = '2';
$data4->customfield_003 = 'Task Desc';

$object4 = new stdclass();

$relations4 = array(
    'zentaoFieldtask' => array(
        'customfield_001' => 'name',
        'customfield_002' => 'pri',
        'customfield_003' => 'desc'
    )
);

r($convertTest->processBuildinFieldDataTest('task', $data4, $object4, $relations4)) && p('name,pri,desc') && e('Task Name,2,Task Desc');

// 测试步骤5：复杂映射场景
$data5 = new stdclass();
$data5->issuetype = 'story';
$data5->valid_field = 'valid_data';
$data5->empty_field = '';
$data5->another_valid = 'another_data';

$object5 = new stdclass();
$object5->pre_existing = 'original_value';

$relations5 = array(
    'zentaoFieldstory' => array(
        'valid_field' => 'title',
        'empty_field' => 'category',
        'another_valid' => 'keywords'
    )
);

r($convertTest->processBuildinFieldDataTest('story', $data5, $object5, $relations5)) && p('title,keywords,pre_existing') && e('valid_data,another_data,original_value');

// 测试步骤6：testcase模块映射真实字段
$data6 = new stdclass();
$data6->issuetype = 'testcase';
$data6->test_field_1 = 'Case Title';
$data6->test_field_2 = 'system';

$object6 = new stdclass();

$relations6 = array(
    'zentaoFieldtestcase' => array(
        'test_field_1' => 'title',
        'test_field_2' => 'stage'
    )
);

r($convertTest->processBuildinFieldDataTest('testcase', $data6, $object6, $relations6)) && p('title,stage') && e('Case Title,system');
