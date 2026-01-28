#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildFieldConfigSchemeData();
timeout=0
cid=15809

- 执行convertTest模块的buildFieldConfigSchemeDataTest方法，参数是$testData1 属性id @1001
- 执行convertTest模块的buildFieldConfigSchemeDataTest方法，参数是$testData2 属性configname @Basic Config Scheme
- 执行convertTest模块的buildFieldConfigSchemeDataTest方法，参数是$testData3 属性description @~~
- 执行convertTest模块的buildFieldConfigSchemeDataTest方法，参数是$testData4 属性fieldid @~~
- 执行convertTest模块的buildFieldConfigSchemeDataTest方法，参数是$testData5 属性configname @Empty Values Scheme

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

$testData1 = array(
    'id' => 1001,
    'name' => 'Test Field Config Scheme',
    'description' => 'This is a test field configuration scheme',
    'fieldid' => 'custom_field_001'
);

$testData2 = array(
    'id' => 1002,
    'name' => 'Basic Config Scheme',
    'description' => 'Basic configuration for testing',
    'fieldid' => 'field_002'
);

$testData3 = array(
    'id' => 1003,
    'name' => 'Minimal Config Scheme',
    'fieldid' => 'field_003'
);

$testData4 = array(
    'id' => 1004,
    'name' => 'No Field ID Scheme',
    'description' => 'Test without field ID'
);

$testData5 = array(
    'id' => 1005,
    'name' => 'Empty Values Scheme'
);

r($convertTest->buildFieldConfigSchemeDataTest($testData1)) && p('id') && e('1001');
r($convertTest->buildFieldConfigSchemeDataTest($testData2)) && p('configname') && e('Basic Config Scheme');
r($convertTest->buildFieldConfigSchemeDataTest($testData3)) && p('description') && e('~~');
r($convertTest->buildFieldConfigSchemeDataTest($testData4)) && p('fieldid') && e('~~');
r($convertTest->buildFieldConfigSchemeDataTest($testData5)) && p('configname') && e('Empty Values Scheme');