#!/usr/bin/env php
<?php
/**

title=测试 docModel->summary();
cid=1

- 测试没有附件的情况 @本页共 <strong>0</strong> 个附件，共计 <strong>0B</strong>，其中<strong></strong>。
- 测试有附件的情况 @本页共 <strong>10</strong> 个附件，共计 <strong>159.2K</strong>，其中<strong>txt 1个、doc 1个、docx 1个、dot 1个、wps 1个、wri 1个、pdf 1个、ppt 1个、pptx 1个、xls 1个</strong>。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

$fileTable = zdTable('file');
$fileTable->size->range('3244,8416,23854,6567,39421');
$fileTable->gen(10);
zdTable('user')->gen(5);

$fileIds[0] = array();
$fileIds[1] = range(1, 10);

$docTester = new docTest();
r($docTester->summaryTest($fileIds[0])) && p() && e('本页共 <strong>0</strong> 个附件，共计 <strong>0B</strong>，其中<strong></strong>。');                                                                                                // 测试没有附件的情况
r($docTester->summaryTest($fileIds[1])) && p() && e('本页共 <strong>10</strong> 个附件，共计 <strong>159.2K</strong>，其中<strong>txt 1个、doc 1个、docx 1个、dot 1个、wps 1个、wri 1个、pdf 1个、ppt 1个、pptx 1个、xls 1个</strong>。'); // 测试有附件的情况
