#!/usr/bin/env php
<?php

/**

title=测试 docZen::prepareDocViewData();
timeout=0
cid=16179

 - 测试产品空间 @1
 - 测试项目空间 @1
 - 测试执行空间 @1
 - 测试自定义空间 @1
 - 测试我的空间 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doczen.unittest.class.php';

global $tester;
$tester->dao->delete()->from(TABLE_DOCLIB)->exec();
$tester->dao->delete()->from(TABLE_DOC)->exec();
$tester->dao->delete()->from(TABLE_DOCCONTENT)->exec();

$tester->dao->insert(TABLE_DOCLIB)->set('id')->eq(1)->set('name')->eq('产品文档库')->set('type')->eq('product')->set('product')->eq(1)->set('acl')->eq('default')->exec();
$tester->dao->insert(TABLE_DOCLIB)->set('id')->eq(2)->set('name')->eq('项目文档库')->set('type')->eq('project')->set('project')->eq(11)->set('acl')->eq('default')->exec();
$tester->dao->insert(TABLE_DOCLIB)->set('id')->eq(3)->set('name')->eq('执行文档库')->set('type')->eq('execution')->set('execution')->eq(101)->set('acl')->eq('default')->exec();
$tester->dao->insert(TABLE_DOCLIB)->set('id')->eq(4)->set('name')->eq('自定义文档库')->set('type')->eq('custom')->set('acl')->eq('default')->exec();
$tester->dao->insert(TABLE_DOCLIB)->set('id')->eq(5)->set('name')->eq('我的文档库')->set('type')->eq('mine')->set('acl')->eq('private')->set('main')->eq(1)->exec();

$tester->dao->insert(TABLE_DOC)->set('id')->eq(1)->set('title')->eq('文档1')->set('lib')->eq(1)->set('type')->eq('text')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(2)->set('title')->eq('文档2')->set('lib')->eq(2)->set('type')->eq('text')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(3)->set('title')->eq('文档3')->set('lib')->eq(3)->set('type')->eq('text')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(4)->set('title')->eq('文档4')->set('lib')->eq(4)->set('type')->eq('text')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(5)->set('title')->eq('文档5')->set('lib')->eq(5)->set('type')->eq('text')->set('version')->eq(1)->exec();

$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(1)->set('title')->eq('文档1')->set('content')->eq('内容1')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(2)->set('title')->eq('文档2')->set('content')->eq('内容2')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(3)->set('title')->eq('文档3')->set('content')->eq('内容3')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(4)->set('title')->eq('文档4')->set('content')->eq('内容4')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(5)->set('title')->eq('文档5')->set('content')->eq('内容5')->set('version')->eq(1)->exec();

zenData('product')->gen(3);
zenData('project')->gen(3);
zenData('group')->gen(3);
zenData('user')->gen(5);

su('admin');

$docTest = new docZenTest();

r($docTest->prepareDocViewDataTest('product', '1', 1, 1)) && p('hasLibPairs,hasGroups,hasUsers') && e('1,1,1');
r($docTest->prepareDocViewDataTest('project', '11', 2, 2)) && p('hasLibPairs,hasGroups,hasUsers') && e('1,1,1');
r($docTest->prepareDocViewDataTest('execution', '101', 3, 3)) && p('hasLibPairs,hasGroups,hasUsers') && e('1,1,1');
r($docTest->prepareDocViewDataTest('custom', '0', 4, 4)) && p('hasLibPairs,hasGroups,hasUsers') && e('1,1,1');
r($docTest->prepareDocViewDataTest('mine', '0', 5, 5)) && p('hasLibPairs,hasGroups,hasUsers') && e('1,1,1');
