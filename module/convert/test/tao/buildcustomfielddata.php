#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildCustomFieldData();
timeout=0
cid=15806

- 执行convertTest模块的buildCustomFieldDataTest方法，参数是$fullData
 - 属性id @12345
 - 属性cfname @CustomField1
 - 属性description @Test custom field description
 - 属性customfieldtypekey @textfield
 - 属性customfieldsearcherkey @textsearcher
- 执行convertTest模块的buildCustomFieldDataTest方法，参数是$partialData
 - 属性id @67890
 - 属性cfname @PartialField
 - 属性description @~~
 - 属性customfieldtypekey @selectlist
 - 属性customfieldsearcherkey @~~
- 执行convertTest模块的buildCustomFieldDataTest方法，参数是$minimalData
 - 属性id @999
 - 属性cfname @MinimalField
 - 属性description @~~
 - 属性customfieldtypekey @~~
 - 属性customfieldsearcherkey @~~
- 执行convertTest模块的buildCustomFieldDataTest方法，参数是$specialData
 - 属性id @field-001
 - 属性cfname @Field with "quotes" & <html> tags
- 执行convertTest模块的buildCustomFieldDataTest方法，参数是$emptyDescData
 - 属性id @555
 - 属性cfname @EmptyDescField
 - 属性description @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 测试步骤：必须包含至少5个测试步骤

// 步骤1：完整自定义字段数据构建
$fullData = array(
    'id' => '12345',
    'name' => 'CustomField1',
    'description' => 'Test custom field description',
    'customfieldtypekey' => 'textfield',
    'customfieldsearcherkey' => 'textsearcher'
);
r($convertTest->buildCustomFieldDataTest($fullData)) && p('id,cfname,description,customfieldtypekey,customfieldsearcherkey') && e('12345,CustomField1,Test custom field description,textfield,textsearcher');

// 步骤2：部分字段缺失数据构建
$partialData = array(
    'id' => '67890',
    'name' => 'PartialField',
    'customfieldtypekey' => 'selectlist'
);
r($convertTest->buildCustomFieldDataTest($partialData)) && p('id,cfname,description,customfieldtypekey,customfieldsearcherkey') && e('67890,PartialField,~~,selectlist,~~');

// 步骤3：必需字段最小数据构建
$minimalData = array(
    'id' => '999',
    'name' => 'MinimalField'
);
r($convertTest->buildCustomFieldDataTest($minimalData)) && p('id,cfname,description,customfieldtypekey,customfieldsearcherkey') && e('999,MinimalField,~~,~~,~~');

// 步骤4：特殊字符和边界值测试
$specialData = array(
    'id' => 'field-001',
    'name' => 'Field with "quotes" & <html> tags',
    'description' => "Multi-line\ndescription\nwith\ttabs",
    'customfieldtypekey' => 'textarea',
    'customfieldsearcherkey' => 'freetext'
);
r($convertTest->buildCustomFieldDataTest($specialData)) && p('id,cfname') && e('field-001,Field with "quotes" & <html> tags');

// 步骤5：空描述字段测试
$emptyDescData = array(
    'id' => '555',
    'name' => 'EmptyDescField',
    'description' => '',
    'customfieldtypekey' => 'datefield',
    'customfieldsearcherkey' => 'datesearcher'
);
r($convertTest->buildCustomFieldDataTest($emptyDescData)) && p('id,cfname,description') && e('555,EmptyDescField,~~');