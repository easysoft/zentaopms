#!/usr/bin/env php
<?php

/**

title=测试 docZen::initDocContext();
timeout=0
cid=16179

 - 测试产品空间文档 @1
 - 测试项目空间文档 @1
 - 测试传入libID @1
 - 测试传入spaceType和space @1
 - 测试quick类型 @1

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

$tester->dao->insert(TABLE_DOC)->set('id')->eq(1)->set('title')->eq('文档1')->set('lib')->eq(1)->set('type')->eq('text')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(2)->set('title')->eq('文档2')->set('lib')->eq(1)->set('type')->eq('text')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(3)->set('title')->eq('文档3')->set('lib')->eq(2)->set('type')->eq('text')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(5)->set('title')->eq('文档5')->set('lib')->eq(3)->set('type')->eq('text')->set('version')->eq(1)->exec();

$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(1)->set('title')->eq('文档1')->set('content')->eq('内容1')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(2)->set('title')->eq('文档2')->set('content')->eq('内容2')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(3)->set('title')->eq('文档3')->set('content')->eq('内容3')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(5)->set('title')->eq('文档5')->set('content')->eq('内容5')->set('version')->eq(1)->exec();

su('admin');

$docTest = new docZenTest();

r($docTest->initDocContextTest(1, 0, '', '')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(2, 1, '', '')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(3, 2, 'product', '1')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(3, 0, '', '')) && p('hasDoc') && e('1');
r($docTest->initDocContextTest(5, 0, 'quick', '')) && p('hasDoc') && e('1');
