#!/usr/bin/env php
<?php

/**

title=测试 programZen::prepareStartExtras();
timeout=0
cid=0

- 执行programTest模块的prepareStartExtrasTest方法，参数是$emptyData 属性status @doing
- 执行programTest模块的prepareStartExtrasTest方法，参数是$dataWithStatus 属性status @doing
- 执行programTest模块的prepareStartExtrasTest方法，参数是$testData 属性lastEditedBy @admin
- 执行$isDateValid @1
- 执行programTest模块的prepareStartExtrasTest方法，参数是$dataWithField 属性testField @test123
- 执行$resultMulti
 - 属性field1 @customValue1
 - 属性field2 @customValue2
- 执行$requiredFields @status,lastEditedBy,lastEditedDate

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$programTest = new programTest();

// 4. 测试步骤

// 步骤1：传入空的form对象，检查是否正确添加status字段
$emptyData = form::data(array());
r($programTest->prepareStartExtrasTest($emptyData)) && p('status') && e('doing');

// 步骤2：传入已有status的form对象，检查status是否被覆盖
$dataWithStatus = form::data(array())->add('status', 'wait');
r($programTest->prepareStartExtrasTest($dataWithStatus)) && p('status') && e('doing');

// 步骤3：检查是否正确添加lastEditedBy字段
$testData = form::data(array());
r($programTest->prepareStartExtrasTest($testData)) && p('lastEditedBy') && e('admin');

// 步骤4：检查lastEditedDate字段是否为当前时间格式
$dateData = form::data(array());
$result = $programTest->prepareStartExtrasTest($dateData);
$isDateValid = (bool)preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $result->lastEditedDate);
r($isDateValid) && p() && e('1');

// 步骤5：传入包含其他字段的form对象，检查原有字段是否保留
$dataWithField = form::data(array())->add('testField', 'test123');
r($programTest->prepareStartExtrasTest($dataWithField)) && p('testField') && e('test123');

// 步骤6：传入包含多个自定义字段的form对象，验证所有字段保留
$multiFieldData = form::data(array())->add('field1', 'customValue1')->add('field2', 'customValue2');
$resultMulti = $programTest->prepareStartExtrasTest($multiFieldData);
r($resultMulti) && p('field1,field2') && e('customValue1,customValue2');

// 步骤7：检查对象的所有必需字段是否都存在
$checkRequiredData = form::data(array());
$resultRequired = $programTest->prepareStartExtrasTest($checkRequiredData);
$requiredFields = array();
if(isset($resultRequired->status)) $requiredFields[] = 'status';
if(isset($resultRequired->lastEditedBy)) $requiredFields[] = 'lastEditedBy';
if(isset($resultRequired->lastEditedDate)) $requiredFields[] = 'lastEditedDate';
r(implode(',', $requiredFields)) && p() && e('status,lastEditedBy,lastEditedDate');