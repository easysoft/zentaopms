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

zenData('doclib')->loadYaml('doclib')->gen(1)->set('id', 1)->set('name', '测试文档库')->set('type', 'custom')->set('parent', 0)->set('deleted', 0)->set('vision', 'rnd')->set('acl', 'open')->exec();
zenData('doc')->loadYaml('doc')->gen(2)->set('lib', 1)->set('deleted', 0)->set('vision', 'rnd')->set('addedBy', 'admin')->exec();
zenData('docaction')->loadYaml('docaction')->gen(1)->set('doc', 1)->set('action', 'collect')->set('actor', 'admin')->exec();

$docs = dao::select('*')->from(TABLE_DOC)->where('lib')->eq(1)->andWhere('deleted')->eq(0)->fetchAll();
$docCount = count($docs);
$draftDoc = array_values(array_filter($docs, function($d){ return $d->status == 'draft'; }));
$normalDoc = array_values(array_filter($docs, function($d){ return $d->status != 'draft'; }));
$searchTitle = !empty($normalDoc) ? $normalDoc[0]->title : '';

$libs = array(1);
$result = $docTest->getDocCountWithPagerTest($libs);
r($result) && p() && e($docCount);

$result = $docTest->getDocCountWithPagerTest($libs, '', $searchTitle);
r($result >= 1) && p() && e('1');

$result = $docTest->getDocCountWithPagerTest($libs, 'collect');
r($result) && p() && e('1');

$result = $docTest->getDocCountWithPagerTest($libs, 'draft');
r(count($draftDoc)) && p() && e('1');

r(gettype($result)) && p() && e('integer');
