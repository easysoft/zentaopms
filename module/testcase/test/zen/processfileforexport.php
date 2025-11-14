#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::processFileForExport();
timeout=0
cid=0

- 执行testcaseTest模块的processFileForExportTest方法 属性id @2
- 执行testcaseTest模块的processFileForExportTest方法 属性id @3
- 执行testcaseTest模块的processFileForExportTest方法 属性id @4
- 执行testcaseTest模块的processFileForExportTest方法 属性id @5
- 执行testcaseTest模块的processFileForExportTest方法 属性id @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

su('admin');

$testcaseTest = new testcaseZenTest();

r($testcaseTest->processFileForExportTest((object)array('id' => 2), array(999 => array((object)array('id' => 100, 'title' => 'test.txt'))))) && p('id') && e('2');
r($testcaseTest->processFileForExportTest((object)array('id' => 3), array(3 => array((object)array('id' => 1, 'title' => 'test.txt'))))) && p('id') && e('3');
r($testcaseTest->processFileForExportTest((object)array('id' => 4), array(4 => array((object)array('id' => 2, 'title' => 'doc1.doc'), (object)array('id' => 3, 'title' => 'doc2.pdf'))))) && p('id') && e('4');
r($testcaseTest->processFileForExportTest((object)array('id' => 5), array(5 => array((object)array('id' => 4, 'title' => 'file.txt'))))) && p('id') && e('5');
r($testcaseTest->processFileForExportTest((object)array('id' => 6), array())) && p('id') && e('6');