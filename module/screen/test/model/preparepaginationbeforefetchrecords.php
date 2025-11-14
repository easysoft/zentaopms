#!/usr/bin/env php
<?php

/**

title=测试 screenModel::preparePaginationBeforeFetchRecords();
timeout=0
cid=18272

- 步骤1：测试传入数组形式的完整分页参数
 - 第0条的pageID属性 @2
 - 第0条的recPerPage属性 @20
 - 第0条的recTotal属性 @100
 - 第1条的index属性 @2
 - 第1条的size属性 @20
 - 第1条的total属性 @100
- 步骤2：测试传入JSON字符串形式的分页参数
 - 第0条的pageID属性 @3
 - 第0条的recPerPage属性 @15
 - 第0条的recTotal属性 @50
 - 第1条的index属性 @3
 - 第1条的size属性 @15
 - 第1条的total属性 @50
- 步骤3：测试传入空数组 @0
- 步骤4：测试传入null @0
- 步骤5：测试传入部分参数（只包含index），其他使用默认值
 - 第0条的pageID属性 @5
 - 第0条的recPerPage属性 @12
 - 第0条的recTotal属性 @0
 - 第1条的index属性 @5
 - 第1条的size属性 @12
 - 第1条的total属性 @0
- 步骤6：测试传入部分参数（只包含size和total）
 - 第0条的pageID属性 @1
 - 第0条的recPerPage属性 @30
 - 第0条的recTotal属性 @200
 - 第1条的index属性 @1
 - 第1条的size属性 @30
 - 第1条的total属性 @200
- 步骤7：测试传入所有自定义参数
 - 第0条的pageID属性 @10
 - 第0条的recPerPage属性 @50
 - 第0条的recTotal属性 @500
 - 第1条的index属性 @10
 - 第1条的size属性 @50
 - 第1条的total属性 @500

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$screenTest = new screenTest();

// 4. 测试步骤

// 步骤1：测试传入数组形式的完整分页参数
$pagination1 = array('index' => 2, 'size' => 20, 'total' => 100);
r($screenTest->preparePaginationBeforeFetchRecordsTest($pagination1)) && p('0:pageID;0:recPerPage;0:recTotal;1:index;1:size;1:total') && e('2;20;100;2;20;100'); // 步骤1：测试传入数组形式的完整分页参数

// 步骤2：测试传入JSON字符串形式的分页参数
$pagination2 = '{"index":3,"size":15,"total":50}';
r($screenTest->preparePaginationBeforeFetchRecordsTest($pagination2)) && p('0:pageID;0:recPerPage;0:recTotal;1:index;1:size;1:total') && e('3;15;50;3;15;50'); // 步骤2：测试传入JSON字符串形式的分页参数

// 步骤3：测试传入空数组
r($screenTest->preparePaginationBeforeFetchRecordsTest(array())) && p() && e('0'); // 步骤3：测试传入空数组

// 步骤4：测试传入null（empty check会返回输入本身，测试框架将其显示为0）
r($screenTest->preparePaginationBeforeFetchRecordsTest(null)) && p() && e('0'); // 步骤4：测试传入null

// 步骤5：测试传入部分参数（只包含index），其他使用默认值
$pagination5 = array('index' => 5);
r($screenTest->preparePaginationBeforeFetchRecordsTest($pagination5)) && p('0:pageID;0:recPerPage;0:recTotal;1:index;1:size;1:total') && e('5;12;0;5;12;0'); // 步骤5：测试传入部分参数（只包含index），其他使用默认值

// 步骤6：测试传入部分参数（只包含size和total）
$pagination6 = array('size' => 30, 'total' => 200);
r($screenTest->preparePaginationBeforeFetchRecordsTest($pagination6)) && p('0:pageID;0:recPerPage;0:recTotal;1:index;1:size;1:total') && e('1;30;200;1;30;200'); // 步骤6：测试传入部分参数（只包含size和total）

// 步骤7：测试传入所有自定义参数
$pagination7 = array('index' => 10, 'size' => 50, 'total' => 500);
r($screenTest->preparePaginationBeforeFetchRecordsTest($pagination7)) && p('0:pageID;0:recPerPage;0:recTotal;1:index;1:size;1:total') && e('10;50;500;10;50;500'); // 步骤7：测试传入所有自定义参数