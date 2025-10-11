#!/usr/bin/env php
<?php

/**

title=测试 caselibModel::getPairs();
timeout=0
cid=0

- 执行caselibTest模块的getPairsTest方法，参数是'all', 'id_desc'
 - 属性8 @Test Lib
 - 属性7 @Case Library
 - 属性6 @测试库2
 - 属性5 @测试库1
- 执行caselibTest模块的getPairsTest方法，参数是'review', 'id_desc'  @0
- 执行caselibTest模块的getPairsTest方法，参数是'all', 'name_asc'
 - 属性7 @Case Library
 - 属性3 @Library01
 - 属性4 @Library02
 - 属性8 @Test Lib
- 执行caselibTest模块的getPairsTest方法，参数是'invalid', 'id_desc'
 - 属性8 @Test Lib
 - 属性7 @Case Library
 - 属性6 @测试库2
 - 属性5 @测试库1
- 执行caselibTest模块的getPairsTest方法，参数是'all', 'id_desc'  @8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

global $tester;
$dao = $tester->dao;

// 清理现有数据
$dao->delete()->from(TABLE_TESTSUITE)->where('type')->eq('library')->exec();

// 直接插入测试数据，避免zendata工具问题
$testLibraries = array(
    array('id' => 1, 'name' => '用例库A', 'product' => 0, 'type' => 'library', 'deleted' => 0, 'addedBy' => 'admin', 'addedDate' => date('Y-m-d H:i:s')),
    array('id' => 2, 'name' => '用例库B', 'product' => 0, 'type' => 'library', 'deleted' => 0, 'addedBy' => 'admin', 'addedDate' => date('Y-m-d H:i:s')),
    array('id' => 3, 'name' => 'Library01', 'product' => 0, 'type' => 'library', 'deleted' => 0, 'addedBy' => 'admin', 'addedDate' => date('Y-m-d H:i:s')),
    array('id' => 4, 'name' => 'Library02', 'product' => 0, 'type' => 'library', 'deleted' => 0, 'addedBy' => 'admin', 'addedDate' => date('Y-m-d H:i:s')),
    array('id' => 5, 'name' => '测试库1', 'product' => 0, 'type' => 'library', 'deleted' => 0, 'addedBy' => 'admin', 'addedDate' => date('Y-m-d H:i:s')),
    array('id' => 6, 'name' => '测试库2', 'product' => 0, 'type' => 'library', 'deleted' => 0, 'addedBy' => 'admin', 'addedDate' => date('Y-m-d H:i:s')),
    array('id' => 7, 'name' => 'Case Library', 'product' => 0, 'type' => 'library', 'deleted' => 0, 'addedBy' => 'admin', 'addedDate' => date('Y-m-d H:i:s')),
    array('id' => 8, 'name' => 'Test Lib', 'product' => 0, 'type' => 'library', 'deleted' => 0, 'addedBy' => 'admin', 'addedDate' => date('Y-m-d H:i:s'))
);

foreach($testLibraries as $library) {
    $dao->insert(TABLE_TESTSUITE)->data($library)->exec();
}

su('admin');

$caselibTest = new caselibTest();

r($caselibTest->getPairsTest('all', 'id_desc')) && p('8,7,6,5') && e('Test Lib,Case Library,测试库2,测试库1');
r($caselibTest->getPairsTest('review', 'id_desc')) && p() && e('0');
r($caselibTest->getPairsTest('all', 'name_asc')) && p('7,3,4,8') && e('Case Library,Library01,Library02,Test Lib');
r($caselibTest->getPairsTest('invalid', 'id_desc')) && p('8,7,6,5') && e('Test Lib,Case Library,测试库2,测试库1');
r(count($caselibTest->getPairsTest('all', 'id_desc'))) && p() && e('8');