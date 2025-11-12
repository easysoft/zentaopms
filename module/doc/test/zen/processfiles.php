#!/usr/bin/env php
<?php

/**

title=测试 docZen::processFiles();
timeout=0
cid=0

- 执行docTest模块的processFilesTest方法，参数是array 第1条的fileName属性 @test1
- 执行docTest模块的processFilesTest方法，参数是array  @0
- 执行docTest模块的processFilesTest方法，参数是array 第3条的fileName属性 @document
- 执行docTest模块的processFilesTest方法，参数是array 第3条的fileIcon属性 @icon-file-word
- 执行docTest模块的processFilesTest方法，参数是array 第4条的fileName属性 @report
- 执行docTest模块的processFilesTest方法，参数是array 第5条的sizeText属性 @1.0K

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

// 准备测试数据
$file1 = new stdclass();
$file1->id         = 1;
$file1->pathname   = '/tmp/test1.png';
$file1->title      = 'test1.png';
$file1->extension  = 'png';
$file1->size       = 2048;
$file1->objectType = 'doc';
$file1->objectID   = 1;

$file2 = new stdclass();
$file2->id         = 2;
$file2->pathname   = '';
$file2->title      = 'test2.jpg';
$file2->extension  = 'jpg';
$file2->size       = 4096;
$file2->objectType = 'bug';
$file2->objectID   = 2;

$file3 = new stdclass();
$file3->id         = 3;
$file3->pathname   = '/tmp/test3.doc';
$file3->title      = 'document.doc';
$file3->extension  = 'doc';
$file3->size       = 10240;
$file3->objectType = 'requirement';
$file3->objectID   = 3;

$file4 = new stdclass();
$file4->id         = 4;
$file4->pathname   = '/tmp/test4.pdf';
$file4->title      = 'report.pdf';
$file4->extension  = 'pdf';
$file4->size       = 512000;
$file4->objectType = 'task';
$file4->objectID   = 4;

$file5 = new stdclass();
$file5->id         = 5;
$file5->pathname   = '/tmp/test5.xlsx';
$file5->title      = 'data.xlsx';
$file5->extension  = 'xlsx';
$file5->size       = 1024;
$file5->objectType = 'story';
$file5->objectID   = 5;

$fileIcon = array(1 => 'icon-file-image', 3 => 'icon-file-word', 4 => 'icon-file-pdf', 5 => 'icon-file-excel');
$sourcePairs = array('doc' => array(1 => 'Doc1'), 'bug' => array(2 => 'Bug2'), 'requirement' => array(3 => 'Req3'), 'task' => array(4 => 'Task4'), 'story' => array(5 => 'Story5'));

r($docTest->processFilesTest(array(1 => $file1), $fileIcon, $sourcePairs, false)) && p('1:fileName') && e('test1');
r($docTest->processFilesTest(array(2 => $file2), $fileIcon, $sourcePairs, false)) && p() && e('0');
r($docTest->processFilesTest(array(3 => $file3), $fileIcon, $sourcePairs, true)) && p('3:fileName') && e('document');
r($docTest->processFilesTest(array(3 => $file3), $fileIcon, $sourcePairs, false)) && p('3:fileIcon') && e('icon-file-word');
r($docTest->processFilesTest(array(4 => $file4), $fileIcon, $sourcePairs, false)) && p('4:fileName') && e('report');
r($docTest->processFilesTest(array(5 => $file5), $fileIcon, $sourcePairs, false)) && p('5:sizeText') && e('1.0K');