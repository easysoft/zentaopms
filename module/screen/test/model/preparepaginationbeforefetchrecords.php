#!/usr/bin/env php
<?php

/**

title=测试 screenModel::preparePaginationBeforeFetchRecords();
timeout=0
cid=0

- 执行$result[1]
 - 属性index @2
 - 属性size @10
 - 属性total @50
- 执行$result[1]
 - 属性index @3
 - 属性size @20
 - 属性total @100
- 执行screenTest模块的preparePaginationBeforeFetchRecordsTest方法，参数是null  @~~
- 执行$result[1]
 - 属性index @1
 - 属性size @15
 - 属性total @0
- 执行screenTest模块的preparePaginationBeforeFetchRecordsTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

su('admin');

$screenTest = new screenTest();

// 测试步骤1：正常的分页数组参数
$normalPagination = array('index' => 2, 'size' => 10, 'total' => 50);
$result = $screenTest->preparePaginationBeforeFetchRecordsTest($normalPagination);
r($result[1]) && p('index,size,total') && e('2,10,50');

// 测试步骤2：JSON字符串分页参数
$jsonPagination = '{"index": 3, "size": 20, "total": 100}';
$result = $screenTest->preparePaginationBeforeFetchRecordsTest($jsonPagination);
r($result[1]) && p('index,size,total') && e('3,20,100');

// 测试步骤3：空的分页参数
r($screenTest->preparePaginationBeforeFetchRecordsTest(null)) && p() && e('~~');

// 测试步骤4：不完整的分页参数，使用默认值
$incompletePagination = array('size' => 15);
$result = $screenTest->preparePaginationBeforeFetchRecordsTest($incompletePagination);
r($result[1]) && p('index,size,total') && e('1,15,0');

// 测试步骤5：空数组分页参数，返回空值
r($screenTest->preparePaginationBeforeFetchRecordsTest(array())) && p() && e('0');