#!/usr/bin/env php
<?php

/*

title=测试 docModel::getDocCountWithPager();
timeout=0
cid=16086

- 步骤1：正常获取文档数量 @2
- 步骤2：搜索关键词返回文档数量 @1
- 步骤3：筛选类型为collect @1
- 步骤4：筛选类型为draft @1
- 步骤5：验证方法返回整型 @integer

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$docTest = new docModelTest();

global $tester;
$tester->dao->delete()->from(TABLE_DOC)->exec();
$tester->dao->delete()->from(TABLE_DOCLIB)->exec();
$tester->dao->delete()->from(TABLE_DOCACTION)->exec();

$tester->dao->insert(TABLE_DOCLIB)
    ->set('id')->eq(1)
    ->set('name')->eq('测试文档库')
    ->set('type')->eq('custom')
    ->set('parent')->eq(0)
    ->set('deleted')->eq(0)
    ->set('vision')->eq('rnd')
    ->set('acl')->eq('open')
    ->exec();

$tester->dao->insert(TABLE_DOC)->set('id')->eq(1)->set('lib')->eq(1)->set('title')->eq('测试文档1')->set('type')->eq('text')->set('status')->eq('normal')->set('deleted')->eq(0)->set('vision')->eq('rnd')->set('addedBy')->eq('admin')->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(2)->set('lib')->eq(1)->set('title')->eq('测试文档2')->set('type')->eq('text')->set('status')->eq('draft')->set('deleted')->eq(0)->set('vision')->eq('rnd')->set('addedBy')->eq('admin')->set('editedBy')->eq('admin')->exec();
$tester->dao->insert(TABLE_DOCACTION)->set('doc')->eq(1)->set('action')->eq('collect')->set('actor')->eq('admin')->exec();

$libs = array(1);
$result = $docTest->getDocCountWithPagerTest($libs);
r($result) && p() && e('2');

$result = $docTest->getDocCountWithPagerTest($libs, '', '测试文档1');
r($result) && p() && e('1');

$result = $docTest->getDocCountWithPagerTest($libs, 'collect');
r($result) && p() && e('1');

$result = $docTest->getDocCountWithPagerTest($libs, 'draft');
r($result) && p() && e('1');

r(gettype($result)) && p() && e('integer');
