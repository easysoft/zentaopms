#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildChangeItemData();
timeout=0
cid=15804

- 执行convertTest模块的buildChangeItemDataTest方法，参数是$testData1 属性id @1001
- 执行convertTest模块的buildChangeItemDataTest方法，参数是$testData1 属性groupid @2001
- 执行convertTest模块的buildChangeItemDataTest方法，参数是$testData1 属性fieldtype @text
- 执行convertTest模块的buildChangeItemDataTest方法，参数是$testData1 属性field @summary
- 执行convertTest模块的buildChangeItemDataTest方法，参数是$testData1 属性oldvalue @old summary
- 执行convertTest模块的buildChangeItemDataTest方法，参数是$testData2 属性oldvalue @~~
- 执行convertTest模块的buildChangeItemDataTest方法，参数是$testData3 属性id @1003
- 执行convertTest模块的buildChangeItemDataTest方法，参数是$testData4 属性oldvalue @2023-01-01
- 执行convertTest模块的buildChangeItemDataTest方法，参数是$testData5 属性newstring @ComponentA

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$convertTest = new convertTest();

$testData1 = array(
    'id' => '1001',
    'group' => '2001',
    'fieldtype' => 'text',
    'field' => 'summary',
    'oldvalue' => 'old summary',
    'oldstring' => 'old string',
    'newvalue' => 'new summary',
    'newstring' => 'new string'
);

$testData2 = array(
    'id' => '1002',
    'group' => '2002',
    'fieldtype' => 'select',
    'field' => 'status'
);

$testData3 = array(
    'id' => '1003',
    'group' => '2003',
    'fieldtype' => 'number',
    'field' => 'priority'
);

$testData4 = array(
    'id' => '1004',
    'group' => '2004',
    'fieldtype' => 'date',
    'field' => 'duedate',
    'oldvalue' => '2023-01-01',
    'newvalue' => '2023-12-31'
);

$testData5 = array(
    'id' => '1005',
    'group' => '2005',
    'fieldtype' => 'checkbox',
    'field' => 'components',
    'newstring' => 'ComponentA'
);

r($convertTest->buildChangeItemDataTest($testData1)) && p('id') && e('1001');
r($convertTest->buildChangeItemDataTest($testData1)) && p('groupid') && e('2001');
r($convertTest->buildChangeItemDataTest($testData1)) && p('fieldtype') && e('text');
r($convertTest->buildChangeItemDataTest($testData1)) && p('field') && e('summary');
r($convertTest->buildChangeItemDataTest($testData1)) && p('oldvalue') && e('old summary');
r($convertTest->buildChangeItemDataTest($testData2)) && p('oldvalue') && e('~~');
r($convertTest->buildChangeItemDataTest($testData3)) && p('id') && e('1003');
r($convertTest->buildChangeItemDataTest($testData4)) && p('oldvalue') && e('2023-01-01');
r($convertTest->buildChangeItemDataTest($testData5)) && p('newstring') && e('ComponentA');