#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';
zdTable('file')->gen(10);
su('admin');

/**

title=测试 transfer->getFiles();
timeout=0
cid=1

- 测试ID为2的bug附件是否存在 @File isset
- 测试ID为3的bug附件是否存在 @No File
- 测试ID为1的task附件是否存在 @File isset
- 测试ID为3的task附件是否存在 @No File

*/

$file1 = new stdclass();
$file2 = new stdclass();
$file3 = new stdclass();
$file7 = new stdclass();
$file8 = new stdclass();

$file1->id = 1;
$file2->id = 2;
$file3->id = 3;
$file7->id = 7;
$file8->id = 8;

$rows = array(1 => $file1, 2 => $file2, 3 => $file3, 7 => $file7, 8 => $file8);

$transfer = new transferTest();
r($transfer->getFilesTest('bug', $rows, 2))  && p('') && e("File isset"); // 测试ID为2的bug附件是否存在
r($transfer->getFilesTest('bug', $rows, 3))  && p('') && e("No File");    // 测试ID为3的bug附件是否存在
r($transfer->getFilesTest('task', $rows, 1)) && p('') && e("File isset"); // 测试ID为1的task附件是否存在
r($transfer->getFilesTest('task', $rows, 3)) && p('') && e("No File");    // 测试ID为3的task附件是否存在
