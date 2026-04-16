#!/usr/bin/env php
<?php

/**
title=测试 docModel->copyDocFiles();
timeout=0
cid=16057

- 测试无附件文档内容复制返回结果 @1
- 测试gid不存在于文件表的文档内容复制 @1
- 测试复制一个附件后文件数量 @1
- 测试复制两个不同gid后文件数量 @2
- 测试复制重复gid后文件数量 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$docTester = new docModelTest();
$now = helper::now();
global $tester;

$tester->dao->delete()->from(TABLE_DOCLIB)->exec();
$tester->dao->delete()->from(TABLE_DOC)->exec();
$tester->dao->delete()->from(TABLE_DOCCONTENT)->exec();
$tester->dao->delete()->from(TABLE_FILE)->exec();

$tester->dao->insert(TABLE_DOCLIB)->set('id')->eq(1)->set('name')->eq('lib1')->set('type')->eq('product')->set('acl')->eq('open')->set('deleted')->eq(0)->exec();

$tester->dao->insert(TABLE_DOC)->set('id')->eq(1)->set('lib')->eq(1)->set('title')->eq('无附件')->set('type')->eq('text')->set('version')->eq(1)->set('status')->eq('normal')->set('acl')->eq('open')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(1)->set('version')->eq(1)->set('type')->eq('text')->set('title')->eq('无附件')->set('rawContent')->eq('{"type":"page"}')->set('content')->eq('')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

$tester->dao->insert(TABLE_DOC)->set('id')->eq(2)->set('lib')->eq(1)->set('title')->eq('gid不存在')->set('type')->eq('text')->set('version')->eq(1)->set('status')->eq('normal')->set('acl')->eq('open')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(2)->set('version')->eq(1)->set('type')->eq('text')->set('title')->eq('gid不存在')->set('rawContent')->eq('{"props":{"sourceId":"NOTEXIST"}}')->set('content')->eq('')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

$tester->dao->insert(TABLE_DOC)->set('id')->eq(10)->set('lib')->eq(1)->set('title')->eq('源文档一个附件')->set('type')->eq('text')->set('version')->eq(1)->set('status')->eq('normal')->set('acl')->eq('open')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(10)->set('version')->eq(1)->set('type')->eq('text')->set('title')->eq('源文档一个附件')->set('rawContent')->eq('{"props":{"sourceId":"GID_A"}}')->set('content')->eq('')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_FILE)->set('id')->eq(1)->set('pathname')->eq('202604/test1.txt')->set('title')->eq('test1.txt')->set('extension')->eq('txt')->set('size')->eq(100)->set('objectType')->eq('doc')->set('objectID')->eq(10)->set('gid')->eq('GID_A')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

$tester->dao->insert(TABLE_DOC)->set('id')->eq(11)->set('lib')->eq(1)->set('title')->eq('源文档两个附件')->set('type')->eq('text')->set('version')->eq(1)->set('status')->eq('normal')->set('acl')->eq('open')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(11)->set('version')->eq(1)->set('type')->eq('text')->set('title')->eq('源文档两个附件')->set('rawContent')->eq('{"props":{"sourceId":"GID_B"},{"props":{"sourceId":"GID_C"}}')->set('content')->eq('')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_FILE)->set('id')->eq(2)->set('pathname')->eq('202604/test2.txt')->set('title')->eq('test2.txt')->set('extension')->eq('txt')->set('size')->eq(200)->set('objectType')->eq('doc')->set('objectID')->eq(11)->set('gid')->eq('GID_B')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_FILE)->set('id')->eq(3)->set('pathname')->eq('202604/test3.txt')->set('title')->eq('test3.txt')->set('extension')->eq('txt')->set('size')->eq(300)->set('objectType')->eq('doc')->set('objectID')->eq(11)->set('gid')->eq('GID_C')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

$tester->dao->insert(TABLE_DOC)->set('id')->eq(12)->set('lib')->eq(1)->set('title')->eq('源文档重复附件')->set('type')->eq('text')->set('version')->eq(1)->set('status')->eq('normal')->set('acl')->eq('open')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOCCONTENT)->set('doc')->eq(12)->set('version')->eq(1)->set('type')->eq('text')->set('title')->eq('源文档重复附件')->set('rawContent')->eq('{"props":{"sourceId":"GID_D"},{"props":{"sourceId":"GID_D"}}')->set('content')->eq('')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_FILE)->set('id')->eq(4)->set('pathname')->eq('202604/test4.txt')->set('title')->eq('test4.txt')->set('extension')->eq('txt')->set('size')->eq(400)->set('objectType')->eq('doc')->set('objectID')->eq(12)->set('gid')->eq('GID_D')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

$tester->dao->insert(TABLE_DOC)->set('id')->eq(100)->set('lib')->eq(1)->set('title')->eq('目标1')->set('type')->eq('text')->set('version')->eq(1)->set('status')->eq('normal')->set('acl')->eq('open')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(101)->set('lib')->eq(1)->set('title')->eq('目标2')->set('type')->eq('text')->set('version')->eq(1)->set('status')->eq('normal')->set('acl')->eq('open')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(102)->set('lib')->eq(1)->set('title')->eq('目标3')->set('type')->eq('text')->set('version')->eq(1)->set('status')->eq('normal')->set('acl')->eq('open')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(103)->set('lib')->eq(1)->set('title')->eq('目标4')->set('type')->eq('text')->set('version')->eq(1)->set('status')->eq('normal')->set('acl')->eq('open')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(104)->set('lib')->eq(1)->set('title')->eq('目标5')->set('type')->eq('text')->set('version')->eq(1)->set('status')->eq('normal')->set('acl')->eq('open')->set('addedBy')->eq('admin')->set('addedDate')->eq($now)->exec();

$sourceContent1 = $tester->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq(1)->fetch();
$sourceContent2 = $tester->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq(2)->fetch();
$sourceContent10 = $tester->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq(10)->fetch();
$sourceContent11 = $tester->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq(11)->fetch();
$sourceContent12 = $tester->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq(12)->fetch();

r($docTester->copyDocFilesTest(100, 'text', $sourceContent1)) && p() && e('1');

r($docTester->copyDocFilesTest(101, 'text', $sourceContent2)) && p() && e('1');

r($docTester->copyDocFilesTest(102, 'text', $sourceContent10)) && p() && e('1');

$count1 = $tester->dao->select('COUNT(*) as c')->from(TABLE_FILE)->where('objectID')->eq(102)->andWhere('objectType')->eq('doc')->fetch('c');
r($count1) && p() && e('1');

r($docTester->copyDocFilesTest(103, 'text', $sourceContent11)) && p() && e('1');

$count2 = $tester->dao->select('COUNT(*) as c')->from(TABLE_FILE)->where('objectID')->eq(103)->andWhere('objectType')->eq('doc')->fetch('c');
r($count2) && p() && e('2');

r($docTester->copyDocFilesTest(104, 'text', $sourceContent12)) && p() && e('1');

$count3 = $tester->dao->select('COUNT(*) as c')->from(TABLE_FILE)->where('objectID')->eq(104)->andWhere('objectType')->eq('doc')->fetch('c');
r($count3) && p() && e('1');
