#!/usr/bin/env php
<?php

/**

title=测试 docModel->copyDoc();
timeout=0
cid=16057

 - 测试复制文档到目标库 @1
 - 测试复制不存在的文档 @0
 - 测试复制文档并复制内容 @1
 - 测试复制文档设置acl @open
 - 测试复制文档设置私有权限 @private

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

$targetData = new stdClass();
$targetData->lib = 1;
$targetData->module = 0;
$targetData->parent = 0;
$targetData->acl = 'open';
$targetData->groups = '';
$targetData->users = '';
$targetData->readGroups = '';
$targetData->readUsers = '';

$docTester->createTest(array('lib' => 1, 'title' => '源文档', 'acl' => 'open', 'parent' => 0));

r($docTester->copyDocTest(1, $targetData)) && p() && e('2');
r($docTester->copyDocTest(999, $targetData)) && p() && e('0');

$targetData2 = new stdClass();
$targetData2->lib = 2;
$targetData2->module = 0;
$targetData2->parent = 0;
$targetData2->acl = 'open';
$targetData2->groups = '';
$targetData2->users = '';
$targetData2->readGroups = '';
$targetData2->readUsers = '';

r($docTester->copyDocTest(1, $targetData2)) && p() && e('3');

$targetData3 = new stdClass();
$targetData3->lib = 1;
$targetData3->module = 0;
$targetData3->parent = 0;
$targetData3->acl = 'open';
$targetData3->groups = '';
$targetData3->users = '';
$targetData3->readGroups = '';
$targetData3->readUsers = '';

r($docTester->copyDocTest(1, $targetData3)) && p() && e('4');

$targetData4 = new stdClass();
$targetData4->lib = 1;
$targetData4->module = 0;
$targetData4->parent = 0;
$targetData4->acl = 'private';
$targetData4->groups = '';
$targetData4->users = '';
$targetData4->readGroups = '';
$targetData4->readUsers = '';

r($docTester->copyDocTest(1, $targetData4)) && p() && e('5');
