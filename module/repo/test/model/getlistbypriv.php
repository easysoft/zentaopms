#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getlistbypriv();
timeout=0
cid=0

- 执行repoTest模块的getListByPrivTest方法，参数是'all'  @0
- 执行repoTest模块的getListByPrivTest方法，参数是'browse'  @0
- 执行repoTest模块的getListByPrivTest方法，参数是'active'  @0
- 执行repoTest模块的getListByPrivTest方法，参数是'closed'  @0
- 执行repoTest模块的getListByPrivTest方法，参数是'private'  @0

*/

su('admin');
$repo = zenData('ops_repo');
$repo->id->range('1');
$repo->name->range('private-list-repo');
$repo->gitUID->range('private-list-uid');
$repo->status->range('active');
$repo->acl->range('open');
$repo->synced->range('0');
$repo->deleted->range('0');
$repo->gen(1);

$repoTest = new repoModelTest();
r($repoTest->getListByPrivTest('all'))     && p() && e('0');
r($repoTest->getListByPrivTest('browse'))  && p() && e('0');
r($repoTest->getListByPrivTest('active'))  && p() && e('0');
r($repoTest->getListByPrivTest('closed'))  && p() && e('0');
r($repoTest->getListByPrivTest('private')) && p() && e('0');
