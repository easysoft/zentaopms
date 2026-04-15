#!/usr/bin/env php
<?php

/**

title=测试 docModel->copyDocFiles();
timeout=0
cid=16057

 - 测试空文件列表 @1
 - 测试复制不存在的单个文件 @1
 - 测试复制不存在的多个文件 @1
 - 测试复制空文件列表第二次 @1
 - 测试复制不存在的文件第三次 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doclib')->gen(5);
zenData('doccontent')->gen(0);
zenData('doc')->gen(0);
zenData('file')->gen(0);
zenData('user')->gen(5);

su('admin');

$docTester = new docModelTest();

r($docTester->copyDocFilesTest(array(), 1)) && p() && e('1');
r($docTester->copyDocFilesTest(array(999), 2)) && p() && e('1');
r($docTester->copyDocFilesTest(array(888, 777), 3)) && p() && e('1');
r($docTester->copyDocFilesTest(array(), 4)) && p() && e('1');
r($docTester->copyDocFilesTest(array(666), 5)) && p() && e('1');
