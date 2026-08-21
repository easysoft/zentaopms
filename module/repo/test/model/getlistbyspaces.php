#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getListBySpaces();
timeout=0
cid=0

- 步骤1：只查询单个空间时返回该空间下有效仓库数量 @1
- 步骤2：空间 3 下的第一个有效仓库可被取到 @1
- 步骤3：空间 3 下的第二个有效仓库可被取到 @1
- 步骤4：仅有已删除仓库的空间返回空结果 @0
- 步骤5：查询多个空间时会合并所有有效仓库 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

global $lang, $app;
if(!isset($lang->codescan)) $lang->codescan = new stdclass();
if(!isset($lang->codescan->exec)) $lang->codescan->exec = 'exec';
if(!isset($lang->codescan->issue)) $lang->codescan->issue = 'issue';

zenData('ops_repo')->gen(0);

$repo = zenData('ops_repo');
$repo->id->range('1-5');
$repo->spaceID->range('1,1,2,3,3');
$repo->product->range('1{5}');
$repo->name->range('repo1,repo2,repo3,repo4,repo5');
$repo->gitUID->range('uid1,uid2,uid3,uid4,uid5');
$repo->status->range('active,importing,active,active,active');
$repo->deleted->range('0,0,1,0,0');
$repo->gen(5);

$repoTest = new repoModelTest();

r($repoTest->getListBySpacesCountTest(array(1))) && p() && e('1');
r($repoTest->getListBySpacesHasKeyTest(array(3), 4)) && p() && e('1');
r($repoTest->getListBySpacesHasKeyTest(array(3), 5)) && p() && e('1');
r($repoTest->getListBySpacesCountTest(array(2))) && p() && e('0');
r($repoTest->getListBySpacesCountTest(array(1, 3))) && p() && e('3');
