#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::processFileForExport();
timeout=0
cid=15555

- 执行caselibTest模块的processFileForExportTest方法，参数是$case1, $emptyFiles, 'is_empty'  @1
- 执行caselibTest模块的processFileForExportTest方法，参数是$case2, $relatedFiles2, 'has_html_link'  @1
- 执行caselibTest模块的processFileForExportTest方法，参数是$case3, $relatedFiles3, 'files_count'  @3
- 执行caselibTest模块的processFileForExportTest方法，参数是$case4, $relatedFiles4, 'has_files'  @1
- 执行caselibTest模块的processFileForExportTest方法，参数是$case5, $relatedFiles5, 'is_empty'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

su('admin');

$caselibTest = new caselibTest();

// 测试步骤1：无关联文件的情况
$case1 = new stdclass();
$case1->id = 1001;
$case1->files = '';
$emptyFiles = array();
r($caselibTest->processFileForExportTest($case1, $emptyFiles, 'is_empty')) && p() && e('1');

// 测试步骤2：有单个关联文件的情况
$case2 = new stdclass();
$case2->id = 1002;
$case2->files = '';
$singleFile = new stdclass();
$singleFile->id = 1001;
$singleFile->title = 'document.pdf';
$relatedFiles2 = array(1002 => array($singleFile));
r($caselibTest->processFileForExportTest($case2, $relatedFiles2, 'has_html_link')) && p() && e('1');

// 测试步骤3：有多个关联文件的情况
$case3 = new stdclass();
$case3->id = 1003;
$case3->files = '';
$file1 = new stdclass();
$file1->id = 1002;
$file1->title = 'image.png';
$file2 = new stdclass();
$file2->id = 1003;
$file2->title = 'report.docx';
$file3 = new stdclass();
$file3->id = 1004;
$file3->title = 'test.txt';
$relatedFiles3 = array(1003 => array($file1, $file2, $file3));
r($caselibTest->processFileForExportTest($case3, $relatedFiles3, 'files_count')) && p() && e('3');

// 测试步骤4：文件标题包含特殊字符的情况
$case4 = new stdclass();
$case4->id = 1004;
$case4->files = '';
$specialFile = new stdclass();
$specialFile->id = 1005;
$specialFile->title = '特殊字符&<>.xlsx';
$relatedFiles4 = array(1004 => array($specialFile));
r($caselibTest->processFileForExportTest($case4, $relatedFiles4, 'has_files')) && p() && e('1');

// 测试步骤5：关联文件数组为空的情况（用例ID不存在于关联文件数组中）
$case5 = new stdclass();
$case5->id = 9999;
$case5->files = '';
$relatedFiles5 = array(1001 => array($singleFile));
r($caselibTest->processFileForExportTest($case5, $relatedFiles5, 'is_empty')) && p() && e('1');