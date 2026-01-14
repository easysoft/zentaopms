#!/usr/bin/env php
<?php

/**

title=测试 apiModel::buildSearchForm();
timeout=0
cid=15089

- 执行apiTest模块的buildSearchFormTest方法，参数是$lib, 1, '/api/search' 属性module @api
- 执行apiTest模块的buildSearchFormTest方法，参数是$emptyLib, 0, '' 属性module @api
- 执行apiTest模块的buildSearchFormTest方法，参数是$lib1, 1, '/api/search', $libs 属性module @api
- 执行apiTest模块的buildSearchFormTest方法，参数是$lib, 1, '/api/search', $libs, 'product' 属性module @productapiDoc
- 执行apiTest模块的buildSearchFormTest方法，参数是$lib, 2, '/api/search/full', $libs, 'project' 属性queryID @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doclib')->gen(5);

su('admin');

$apiTest = new apiModelTest();

// 步骤1：正常情况测试 - 传入有效的lib对象
$lib = new stdClass();
$lib->id = 1;
$lib->name = 'Test API Library';
$lib->type = 'api';

r($apiTest->buildSearchFormTest($lib, 1, '/api/search')) && p('module') && e('api');

// 步骤2：边界值测试 - 传入具有空属性的lib对象
$emptyLib = new stdClass();
$emptyLib->id = '';
$emptyLib->name = '';
r($apiTest->buildSearchFormTest($emptyLib, 0, '')) && p('module') && e('api');

// 步骤3：多库测试 - 传入libs数组
$libs = array();
$lib1 = new stdClass();
$lib1->id = 1;
$lib1->name = 'Lib1';
$lib1->type = 'api';
$lib2 = new stdClass();
$lib2->id = 2;
$lib2->name = 'Lib2';
$lib2->type = 'api';
$libs[] = $lib1;
$libs[] = $lib2;

r($apiTest->buildSearchFormTest($lib1, 1, '/api/search', $libs)) && p('module') && e('api');

// 步骤4：类型测试 - 传入type参数和libs数组
r($apiTest->buildSearchFormTest($lib, 1, '/api/search', $libs, 'product')) && p('module') && e('productapiDoc');

// 步骤5：完整参数测试 - 传入所有参数
r($apiTest->buildSearchFormTest($lib, 2, '/api/search/full', $libs, 'project')) && p('queryID') && e('2');