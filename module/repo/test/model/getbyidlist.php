#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getByIdList();
timeout=0
cid=18049

- 测试正常获取多个存在的ID >> 4
- 测试获取单个存在ID验证name >> repo1
- 测试不存在的ID >> 0
- 测试空ID列表 >> 0
- 测试混合存在不存在的ID >> 2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$repo = zenData('ops_repo');
$repo->id->range('1-8');
$repo->spaceID->range('1{8}');
$repo->product->range('1{4},2{4}');
$repo->name->range('repo1,repo2,repo3,repo4,repo5,repo6,repo7,repo8');
$repo->gitUID->range('id-list-uid-1,id-list-uid-2,id-list-uid-3,id-list-uid-4,id-list-uid-5,id-list-uid-6,id-list-uid-7,id-list-uid-8');
$repo->status->range('active{8}');
$repo->deleted->range('0{6},1{2}');
$repo->gen(8);

su('admin');
$repoTest = new repoModelTest();

r($repoTest->getByIdListCountTest(array(1,2,3,4))) && p() && e('4');
r($repoTest->getByIdListTest(array(1))) && p('1:name') && e('repo1');
r($repoTest->getByIdListCountTest(array(999,1000))) && p() && e('0');
r($repoTest->getByIdListCountTest(array())) && p() && e('0');
r($repoTest->getByIdListCountTest(array(1,2,999,1000))) && p() && e('2');
