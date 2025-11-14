#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraFile();
timeout=0
cid=15858

- 执行convertTest模块的importJiraFileTest方法，参数是array  @true
- 执行convertTest模块的importJiraFileTest方法，参数是array  @true
- 执行convertTest模块的importJiraFileTest方法，参数是array  @true
- 执行convertTest模块的importJiraFileTest方法，参数是array  @true
- 执行convertTest模块的importJiraFileTest方法，参数是array  @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

zendata('user')->loadYaml('user', false, 2)->gen(10);
zendata('product')->gen(5);
zendata('story')->gen(10);
zendata('task')->gen(10);
zendata('bug')->gen(10);

$fileTable = zenData('file');
$fileTable->id->range('1-10');
$fileTable->pathname->range('202309/11/112230{3}.txt,202309/11/112231{3}.pdf,202309/11/112232{3}.jpg');
$fileTable->title->range('test{3}.txt,document{3}.pdf,image{3}.jpg');
$fileTable->extension->range('txt{3},pdf{3},jpg{3}');
$fileTable->size->range('1024-10240');
$fileTable->objectType->range('story{5},task{3},bug{2}');
$fileTable->objectID->range('1-10');
$fileTable->addedBy->range('admin{5},user1{3},user2{2}');
$fileTable->gen(0);

su('admin');

$convertTest = new convertTest();

$fileData1 = new stdClass();
$fileData1->id = 1;
$fileData1->issueid = 1;
$fileData1->filename = 'test.txt';
$fileData1->filesize = 1024;
$fileData1->author = 'admin';
$fileData1->created = '2023-09-11 10:30:00';

$fileData2 = new stdClass();
$fileData2->id = 2;
$fileData2->issueid = 2;
$fileData2->filename = 'document.pdf';
$fileData2->filesize = 2048;
$fileData2->author = 'user1';
$fileData2->created = '2023-09-11 11:00:00';

$fileData3 = new stdClass();
$fileData3->id = 3;
$fileData3->issueid = 999;
$fileData3->filename = 'nonexist.txt';
$fileData3->filesize = 512;
$fileData3->author = 'user2';
$fileData3->created = '2023-09-11 12:00:00';

$fileData4 = new stdClass();
$fileData4->id = 4;
$fileData4->issueid = 1;
$fileData4->filename = 'image.jpg';
$fileData4->filesize = 4096;
$fileData4->author = 'admin';
$fileData4->created = '2023-09-11 13:00:00';

$fileData5 = new stdClass();
$fileData5->id = 5;
$fileData5->issueid = 3;
$fileData5->filename = 'story_file.docx';
$fileData5->filesize = 8192;
$fileData5->author = 'user1';
$fileData5->created = '2023-09-11 14:00:00';

r($convertTest->importJiraFileTest(array($fileData1, $fileData2))) && p() && e('true');
r($convertTest->importJiraFileTest(array())) && p() && e('true');
r($convertTest->importJiraFileTest(array($fileData3))) && p() && e('true');
r($convertTest->importJiraFileTest(array($fileData4))) && p() && e('true');
r($convertTest->importJiraFileTest(array($fileData1, $fileData2, $fileData5))) && p() && e('true');