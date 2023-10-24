#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->createFromImport();
cid=1
pid=1

测试直接导入数据 >> 这个是测试用例1,这个是测试用例2,导入测试用例1,导入测试用例2
测试导入数据覆盖原有数据 >> 导入覆盖的用例1,导入覆盖的用例2,导入测试用例1,导入测试用例2
测试导入数据不覆盖原有数据 >> 导入覆盖的用例1,导入覆盖的用例2,导入测试用例1,导入测试用例2,导入插入的用例1,导入插入的用例2

*/

$productID = 1;

$param1 = array('id' => array('1' => 1, '2' => 2), 'title' => array('1' => '导入覆盖的用例1', '2' => '导入覆盖的用例2'), 'insert' => '0');
$param2 = array('id' => array('1' => 1, '2' => 2), 'title' => array('1' => '导入插入的用例1', '2' => '导入插入的用例2'), 'insert' => '1');

$testcase = new testcaseTest();

r($testcase->createFromImportTest($productID))          && p() && e('这个是测试用例1,这个是测试用例2,导入测试用例1,导入测试用例2');                                 // 测试直接导入数据
r($testcase->createFromImportTest($productID, $param1)) && p() && e('导入覆盖的用例1,导入覆盖的用例2,导入测试用例1,导入测试用例2');                                 // 测试导入数据覆盖原有数据
r($testcase->createFromImportTest($productID, $param2)) && p() && e('导入覆盖的用例1,导入覆盖的用例2,导入测试用例1,导入测试用例2,导入插入的用例1,导入插入的用例2'); // 测试导入数据不覆盖原有数据
