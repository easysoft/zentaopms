#!/usr/bin/env php
<?php
/**

title=测试 docModel->deleteFiles();
timeout=0
cid=1

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

$docTester = new docTest();
r($docTester->deleteFilesTest($docIdList[0])) && p()    && e('0'); // 测试空数据
r($docTester->deleteFilesTest($docIdList[1])) && p('1') && e('1'); // 测试正常数据
