#!/usr/bin/env php
<?php

/*

title=测试 docModel::getDocsWithPager();
timeout=0
cid=16086

- 步骤1：正常获取文档列表 @array
- 步骤2：搜索关键词返回 @1
- 步骤3：筛选类型为collect @1
- 步骤4：筛选类型为draft @1
- 步骤5：验证返回数组 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$docTest = new docModelTest();

global $tester;
$tester->dao->delete()->from(TABLE_DOC)->exec();
$tester->dao->delete()->from(TABLE_DOCLIB)->exec();
$tester->dao->delete()->from(TABLE_DOCACTION)->exec();
$tester->dao->delete()->from(TABLE_DOCCONTENT)->exec();

$tester->dao->insert(TABLE_DOCLIB)->set('id')->eq(1)->set('name')->eq('测试文档库')->set('type')->eq('custom')->set('parent')->eq(0)->set('deleted')->eq(0)->set('vision')->eq('rnd')->set('acl')->eq('open')->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(1)->set('lib')->eq(1)->set('module')->eq(0)->set('title')->eq('测试文档1')->set('type')->eq('text')->set('status')->eq('normal')->set('deleted')->eq(0)->set('vision')->eq('rnd')->set('addedBy')->eq('admin')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOC)->set('id')->eq(2)->set('lib')->eq(1)->set('module')->eq(0)->set('title')->eq('测试文档2')->set('type')->eq('text')->set('status')->eq('draft')->set('deleted')->eq(0)->set('vision')->eq('rnd')->set('addedBy')->eq('admin')->set('editedBy')->eq('admin')->set('version')->eq(1)->exec();
$tester->dao->insert(TABLE_DOCACTION)->set('doc')->eq(1)->set('action')->eq('collect')->set('actor')->eq('admin')->exec();

$pager = new stdClass();
$pager->page = 1;
$pager->recPerPage = 20;

$libs = array(1);
$result = $docTest->getDocsWithPagerTest($libs, 'custom', 0, false, '', 'id_desc', $pager);
r(gettype($result)) && p() && e('array');

$searchLibs = array(1);
$pager1 = new stdClass();
$pager1->page = 1;
$pager1->recPerPage = 20;
$result = $docTest->getDocsWithPagerTest($searchLibs, 'custom', 0, false, '', 'id_desc', $pager1, '测试文档1');
r(count($result)) && p() && e('1');

$pager2 = new stdClass();
$pager2->page = 1;
$pager2->recPerPage = 20;
$result = $docTest->getDocsWithPagerTest($libs, 'custom', 0, false, 'collect', 'id_desc', $pager2);
r(count($result)) && p() && e('1');

$pager3 = new stdClass();
$pager3->page = 1;
$pager3->recPerPage = 20;
$result = $docTest->getDocsWithPagerTest($libs, 'custom', 0, false, 'draft', 'id_desc', $pager3);
r(count($result)) && p() && e('1');

r(!dao::isError()) && p() && e('1');
