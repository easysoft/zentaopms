#!/usr/bin/env php
<?php

/**

title=测试 aiModel::savePromptFields();
timeout=0
cid=0

- 执行aiTest模块的savePromptFieldsTest方法，参数是1, $testData1  @2
- 执行aiTest模块的savePromptFieldsTest方法，参数是2, $testData2  @1
- 执行aiTest模块的savePromptFieldsTest方法，参数是3, $testData3  @1
- 执行aiTest模块的savePromptFieldsTest方法，参数是4, $testData4  @0
- 执行aiTest模块的savePromptFieldsTest方法，参数是999, $testData5  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$testData1 = new stdClass();
$testData1->fields = array(
    (object)array('appID' => 1, 'name' => '字段A', 'type' => 'text', 'options' => null, 'required' => '1'),
    (object)array('appID' => 1, 'name' => '字段B', 'type' => 'text', 'options' => null, 'required' => '1')
);
r($aiTest->savePromptFieldsTest(1, $testData1->fields)) && p() && e('2');

$testData2 = new stdClass();
$testData2->fields = array(
    (object)array('appID' => 2, 'name' => '字段C', 'type' => 'textarea', 'options' => null, 'required' => '0')
);
r($aiTest->savePromptFieldsTest(2, $testData2->fields)) && p() && e('1');

$testData3 = new stdClass();
$testData3->fields = array(
    (object)array('appID' => 3, 'name' => '字段D', 'type' => 'radio', 'options' => '选项1,选项2', 'required' => '1')
);
r($aiTest->savePromptFieldsTest(3, $testData3->fields)) && p() && e('1');

$testData4 = new stdClass();
$testData4->fields = array();
r($aiTest->savePromptFieldsTest(4, $testData4->fields)) && p() && e('0');

$testData5 = new stdClass();
$testData5->fields = array(
    (object)array('appID' => 999, 'name' => '字段E', 'type' => 'checkbox', 'options' => '可选1,可选2', 'required' => '0')
);
r($aiTest->savePromptFieldsTest(999, $testData5->fields)) && p() && e('1');
