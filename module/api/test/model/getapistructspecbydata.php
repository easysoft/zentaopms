#!/usr/bin/env php
<?php

/**

title=测试 apiModel::getApiStructSpecByData();
timeout=0
cid=15105

- 执行apiTest模块的getApiStructSpecByDataTest方法，参数是$fullData 
 - 属性name @TestStruct
 - 属性type @object
 - 属性desc @Test description
 - 属性version @2
 - 属性attribute @{"field1": "string"}
 - 属性addedBy @testuser
 - 属性addedDate @2024-01-01 12:00:00
- 执行apiTest模块的getApiStructSpecByDataTest方法，参数是$minData 属性name @MinimalStruct
- 执行apiTest模块的getApiStructSpecByDataTest方法，参数是$emptyFieldsData 属性name @EmptyFieldsStruct
- 执行apiTest模块的getApiStructSpecByDataTest方法，参数是$missingOptionalData 
 - 属性name @MissingOptionalStruct
 - 属性type @array
 - 属性desc @Missing optional fields
- 执行apiTest模块的getApiStructSpecByDataTest方法，参数是$zeroVersionData 
 - 属性name @ZeroVersionStruct
 - 属性type @string
 - 属性desc @Version zero test
 - 属性version @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

su('admin');

$apiTest = new apiTest();

// 测试步骤1：正常完整数据输入
$fullData = new stdclass();
$fullData->name = 'TestStruct';
$fullData->type = 'object';
$fullData->desc = 'Test description';
$fullData->version = 2;
$fullData->attribute = '{"field1": "string"}';
$fullData->addedBy = 'testuser';
$fullData->addedDate = '2024-01-01 12:00:00';

r($apiTest->getApiStructSpecByDataTest($fullData)) && p('name,type,desc,version,attribute,addedBy,addedDate') && e('TestStruct,object,Test description,2,{"field1": "string"},testuser,2024-01-01 12:00:00');

// 测试步骤2：最小必需字段数据输入
$minData = new stdclass();
$minData->name = 'MinimalStruct';

r($apiTest->getApiStructSpecByDataTest($minData)) && p('name') && e('MinimalStruct');

// 测试步骤3：部分字段为空值的数据输入
$emptyFieldsData = new stdclass();
$emptyFieldsData->name = 'EmptyFieldsStruct';
$emptyFieldsData->type = '';
$emptyFieldsData->desc = '';
$emptyFieldsData->version = '';
$emptyFieldsData->attribute = '';

r($apiTest->getApiStructSpecByDataTest($emptyFieldsData)) && p('name') && e('EmptyFieldsStruct');

// 测试步骤4：缺失可选字段的数据输入
$missingOptionalData = new stdclass();
$missingOptionalData->name = 'MissingOptionalStruct';
$missingOptionalData->type = 'array';
$missingOptionalData->desc = 'Missing optional fields';

r($apiTest->getApiStructSpecByDataTest($missingOptionalData)) && p('name,type,desc') && e('MissingOptionalStruct,array,Missing optional fields');

// 测试步骤5：version为0的边界值测试
$zeroVersionData = new stdclass();
$zeroVersionData->name = 'ZeroVersionStruct';
$zeroVersionData->type = 'string';
$zeroVersionData->desc = 'Version zero test';
$zeroVersionData->version = 0;
$zeroVersionData->attribute = '{"test": "value"}';

r($apiTest->getApiStructSpecByDataTest($zeroVersionData)) && p('name,type,desc,version') && e('ZeroVersionStruct,string,Version zero test,1');