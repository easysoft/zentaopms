#!/usr/bin/env php
<?php

/*

title=测试 docModel::getDocsWithPager();
timeout=0
cid=16086

- 步骤1：正常获取文档列表 @array
- 步骤2：筛选类型为collect @1
- 步骤3：筛选类型为draft @1
- 步骤4：搜索关键词返回 @1
- 步骤5：验证方法执行无错误 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('docaction')->loadYaml('docaction')->gen(30);
zenData('doclib')->loadYaml('doclib')->gen(30);
zenData('doc')->loadYaml('doc')->gen(50);
zenData('user')->gen(5);

su('admin');

$docTest = new docModelTest();

$pager = new stdClass();
$pager->page = 1;
$pager->recPerPage = 20;

$libs = array(11);
$result = $docTest->getDocsWithPagerTest($libs, 'product', 0, false, '', 'id_desc', $pager);
r(gettype($result)) && p() && e('array');

$pager2 = new stdClass();
$pager2->page = 1;
$pager2->recPerPage = 20;
$result = $docTest->getDocsWithPagerTest($libs, 'product', 0, false, 'collect', 'id_desc', $pager2);
r(count($result)) && p() && e('2');

$pager3 = new stdClass();
$pager3->page = 1;
$pager3->recPerPage = 20;
$result = $docTest->getDocsWithPagerTest($libs, 'product', 0, false, 'draft', 'id_desc', $pager3);
r(count($result)) && p() && e('1');

$pager4 = new stdClass();
$pager4->page = 1;
$pager4->recPerPage = 20;
$result = $docTest->getDocsWithPagerTest($libs, 'product', 0, false, '', 'id_desc', $pager4, '文档');
r(count($result)) && p() && e('3');

r(!dao::isError()) && p() && e('1');
