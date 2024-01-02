#!/usr/bin/env php
<?php
/**

title=测试 docModel->deleteFiles();
cid=1

- 测试空数据 @0
- 测试正常数据属性1 @1
- 测试有不存在的数据属性1 @1
- 测试不存在的数据 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$fileTable = zdTable('file')->config('file');
$fileTable->id->range('1-5');
$fileTable->deleted->range('0');
$fileTable->gen(5);

zdTable('user')->gen(5);
su('admin');

$docIdList[] = array();
$docIdList[] = range(1, 5);
$docIdList[] = array(1, 2, 100, 101);
$docIdList[] = range(100, 105);

$docTester = new docTest();
r($docTester->deleteFilesTest($docIdList[0])) && p()    && e('0'); // 测试空数据
r($docTester->deleteFilesTest($docIdList[1])) && p('1') && e('1'); // 测试正常数据
r($docTester->deleteFilesTest($docIdList[2])) && p('1') && e('1'); // 测试有不存在的数据
r($docTester->deleteFilesTest($docIdList[3])) && p()    && e('0'); // 测试不存在的数据
