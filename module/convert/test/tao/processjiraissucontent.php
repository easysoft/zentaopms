#!/usr/bin/env php
<?php

/**

title=测试 convertTao::processJiraIssueContent();
timeout=0
cid=0

- 执行convertTest模块的processJiraIssueContentTest方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

$table = zenData('jiratmprelation');
$table->AType->range('jissue');
$table->AID->range('1-20');
$table->BType->range('astory,abug,atask,aticket,afeedback');
$table->BID->range('1-5');
$table->gen(10);

$fileTable = zenData('file');
$fileTable->title->range('image.png,doc.pdf,file.txt');
$fileTable->objectType->range('story,bug,task');
$fileTable->objectID->range('1-3');
$fileTable->gen(5);

su('admin');

$convertTest = new convertTest();

r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'astory', 'BID' => 2)
))) && p() && e('1');

r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'abug', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 2)
))) && p() && e('1');

r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'atask', 'BID' => 1),
    (object)array('BType' => 'atask', 'BID' => 2)
))) && p() && e('1');

r($convertTest->processJiraIssueContentTest(array(
    (object)array('BType' => 'astory', 'BID' => 1),
    (object)array('BType' => 'abug', 'BID' => 1),
    (object)array('BType' => 'atask', 'BID' => 1)
))) && p() && e('1');

r($convertTest->processJiraIssueContentTest(array())) && p() && e('1');