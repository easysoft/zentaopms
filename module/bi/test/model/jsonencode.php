#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 biModel::jsonEncode();
timeout=0
cid=15191

- 步骤1：空值情况 @0
- 步骤2：标量情况 @test_string
- 步骤3：数组情况 @{"key":"value"}
- 步骤4：对象情况 @{"name":"test","id":1}

- 步骤5：复杂数组情况 @{"users":["admin","user1"],"count":2}

*/

$biTest = new biModelTest();

r($biTest->jsonEncodeTest(null)) && p() && e('0'); // 步骤1：空值情况
r($biTest->jsonEncodeTest('test_string')) && p() && e('test_string'); // 步骤2：标量情况
r($biTest->jsonEncodeTest(array('key' => 'value'))) && p() && e('{"key":"value"}'); // 步骤3：数组情况
r($biTest->jsonEncodeTest((object)array('name' => 'test', 'id' => 1))) && p() && e('{"name":"test","id":1}'); // 步骤4：对象情况
r($biTest->jsonEncodeTest(array('users' => array('admin', 'user1'), 'count' => 2))) && p() && e('{"users":["admin","user1"],"count":2}'); // 步骤5：复杂数组情况