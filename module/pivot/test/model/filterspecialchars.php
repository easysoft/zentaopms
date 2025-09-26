#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::filterSpecialChars();
timeout=0
cid=0

- 执行pivotTest模块的filterSpecialCharsTest方法，参数是$objectRecords 第0条的name属性 @test<name>
- 执行pivotTest模块的filterSpecialCharsTest方法，参数是$arrayRecords 第0条的name属性 @test<array>
- 执行pivotTest模块的filterSpecialCharsTest方法，参数是array  @0
- 执行pivotTest模块的filterSpecialCharsTest方法，参数是$mixedRecords 第0条的name属性 @test<mixed>
- 执行pivotTest模块的filterSpecialCharsTest方法，参数是$complexRecords 第0条的content属性 @<div>&nbsp;Hello World&nbsp;</div>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivotTest = new pivotTest();

// 步骤1：测试正常对象记录过滤特殊字符
$objectRecords = array();
$record1 = new stdClass();
$record1->name = '"test&lt;name&gt;"';
$record1->desc = '&quot;desc&quot;&amp;content';
$record1->id = 1;
$objectRecords[] = $record1;

r($pivotTest->filterSpecialCharsTest($objectRecords)) && p('0:name') && e('test<name>');

// 步骤2：测试正常数组记录过滤特殊字符
$arrayRecords = array();
$arrayRecords[] = array(
    'name' => '"test&lt;array&gt;"',
    'desc' => '&quot;array desc&quot;',
    'id' => 2
);

r($pivotTest->filterSpecialCharsTest($arrayRecords)) && p('0:name') && e('test<array>');

// 步骤3：测试空记录数组
r($pivotTest->filterSpecialCharsTest(array())) && p() && e('0');

// 步骤4：测试包含非字符串字段的记录
$mixedRecords = array();
$mixedRecord = new stdClass();
$mixedRecord->name = '"test&lt;mixed&gt;"';
$mixedRecord->id = 123;
$mixedRecord->active = true;
$mixedRecord->price = 99.99;
$mixedRecords[] = $mixedRecord;

r($pivotTest->filterSpecialCharsTest($mixedRecords)) && p('0:name') && e('test<mixed>');

// 步骤5：测试包含复杂HTML字符的记录
$complexRecords = array();
$complexRecord = array(
    'content' => '"&lt;div&gt;&amp;nbsp;&quot;Hello World&quot;&amp;nbsp;&lt;/div&gt;"',
    'title' => '&amp;lt;Title&amp;gt;'
);
$complexRecords[] = $complexRecord;

r($pivotTest->filterSpecialCharsTest($complexRecords)) && p('0:content') && e('<div>&nbsp;Hello World&nbsp;</div>');