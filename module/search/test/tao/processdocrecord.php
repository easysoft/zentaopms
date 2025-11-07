#!/usr/bin/env php
<?php

/**

title=测试 searchTao::processDocRecord();
timeout=0
cid=0

- 测试普通文档记录(无assetLib)属性url @doc-view-1.html
- 测试资产库文档记录(assetLibType为practice)属性url @assetlib-practiceView-3.html
- 测试资产库文档记录(assetLibType为component)属性url @assetlib-componentView-5.html
- 测试资产库文档记录(assetLibType为空)属性url @assetlib-componentView-7.html
- 测试文档记录的URL是否包含正确的objectID属性url @doc-view-9.html

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zendata('doc')->loadYaml('processdocrecord', false, 2)->gen(10);

su('admin');

$searchTest = new searchTaoTest();

$record1 = new stdClass();
$record1->objectID = 1;
$record1->objectType = 'doc';

$doc1 = new stdClass();
$doc1->id = 1;
$doc1->assetLib = 0;
$doc1->assetLibType = '';

$objectList1 = array('doc' => array(1 => $doc1));

$record2 = new stdClass();
$record2->objectID = 3;
$record2->objectType = 'doc';

$doc2 = new stdClass();
$doc2->id = 3;
$doc2->assetLib = 1;
$doc2->assetLibType = 'practice';

$objectList2 = array('doc' => array(3 => $doc2));

$record3 = new stdClass();
$record3->objectID = 5;
$record3->objectType = 'doc';

$doc3 = new stdClass();
$doc3->id = 5;
$doc3->assetLib = 2;
$doc3->assetLibType = 'component';

$objectList3 = array('doc' => array(5 => $doc3));

$record4 = new stdClass();
$record4->objectID = 7;
$record4->objectType = 'doc';

$doc4 = new stdClass();
$doc4->id = 7;
$doc4->assetLib = 1;
$doc4->assetLibType = '';

$objectList4 = array('doc' => array(7 => $doc4));

$record5 = new stdClass();
$record5->objectID = 9;
$record5->objectType = 'doc';

$doc5 = new stdClass();
$doc5->id = 9;
$doc5->assetLib = 0;
$doc5->assetLibType = '';

$objectList5 = array('doc' => array(9 => $doc5));

r($searchTest->processDocRecordTest($record1, $objectList1)) && p('url') && e('doc-view-1.html'); // 测试普通文档记录(无assetLib)
r($searchTest->processDocRecordTest($record2, $objectList2)) && p('url') && e('assetlib-practiceView-3.html'); // 测试资产库文档记录(assetLibType为practice)
r($searchTest->processDocRecordTest($record3, $objectList3)) && p('url') && e('assetlib-componentView-5.html'); // 测试资产库文档记录(assetLibType为component)
r($searchTest->processDocRecordTest($record4, $objectList4)) && p('url') && e('assetlib-componentView-7.html'); // 测试资产库文档记录(assetLibType为空)
r($searchTest->processDocRecordTest($record5, $objectList5)) && p('url') && e('doc-view-9.html'); // 测试文档记录的URL是否包含正确的objectID