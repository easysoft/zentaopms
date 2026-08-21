#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getListByProduct();
timeout=0
cid=18071

- 执行repoTest模块的getListByProductTest方法，参数是1 第1条的name属性 @repo1
- 执行repoTest模块的getListByProductTest方法，参数是2 第3条的name属性 @repo3
- 执行repoTest模块的getListByProductTest方法，参数是1, 2 第1条的name属性 @repo1
- 执行repoTest模块的getListByProductCountTest方法，参数是999  @0
- 执行repoTest模块的getListByProductCountTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$repo = zenData('ops_repo');
$repo->id->range('1-5');
$repo->spaceID->range('1{5}');
$repo->product->range('1,1,2,1,3');
$repo->name->range('repo1,repo2,repo3,repo4,repo5');
$repo->gitUID->range('product-repo-uid-1,product-repo-uid-2,product-repo-uid-3,product-repo-uid-4,product-repo-uid-5');
$repo->status->range('active{5}');
$repo->deleted->range('0{5}');
$repo->gen(5);

su('admin');
$repoTest = new repoModelTest();

r($repoTest->getListByProductTest(1)) && p('1:name') && e('repo1');
r($repoTest->getListByProductTest(2)) && p('3:name') && e('repo3');
r($repoTest->getListByProductTest(1,2)) && p('1:name') && e('repo1');
r($repoTest->getListByProductCountTest(999)) && p() && e('0');
r($repoTest->getListByProductCountTest(0)) && p() && e('0');
