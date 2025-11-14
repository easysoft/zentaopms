#!/usr/bin/env php
<?php

/**

title=测试 docZen::responseAfterCreate();
timeout=0
cid=16215

- 步骤1:测试创建普通文档
 - 属性result @success
 - 属性id @1
- 步骤2:测试创建文档模板
 - 属性result @success
 - 属性id @2
- 步骤3:测试不同文档ID
 - 属性result @success
 - 属性id @100
- 步骤4:测试包含文件的文档
 - 属性result @success
 - 属性id @4
- 步骤5:测试不包含文件的文档
 - 属性result @success
 - 属性id @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

$docResult1 = array('id' => 1, 'title' => 'Test Doc');
$docResult2 = array('id' => 2, 'title' => 'Test Template');
$docResult3 = array('id' => 100, 'title' => 'Large ID Test');
$docResult4 = array('id' => 4, 'title' => 'Doc with Files', 'files' => array('file1.txt', 'file2.pdf'));
$docResult5 = array('id' => 5, 'title' => 'Doc without Files');

r($docTest->responseAfterCreateTest($docResult1, 'doc')) && p('result,id') && e('success,1'); // 步骤1:测试创建普通文档
r($docTest->responseAfterCreateTest($docResult2, 'docTemplate')) && p('result,id') && e('success,2'); // 步骤2:测试创建文档模板
r($docTest->responseAfterCreateTest($docResult3, 'doc')) && p('result,id') && e('success,100'); // 步骤3:测试不同文档ID
r($docTest->responseAfterCreateTest($docResult4, 'doc')) && p('result,id') && e('success,4'); // 步骤4:测试包含文件的文档
r($docTest->responseAfterCreateTest($docResult5, 'doc')) && p('result,id') && e('success,5'); // 步骤5:测试不包含文件的文档