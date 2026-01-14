#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getListByProduct();
timeout=0
cid=18071

- 执行repoTest模块的getListByProductTest方法，参数是1 
 - 第1条的name属性 @repo1
 - 第2条的name属性 @repo2
 - 第4条的name属性 @repo4
- 执行repoTest模块的getListByProductTest方法，参数是2, 'Git' 第3条的name属性 @repo3
- 执行repoTest模块的getListByProductTest方法，参数是1, '', 2 
 - 第1条的name属性 @repo1
 - 第2条的name属性 @repo2
- 执行repoTest模块的getListByProductTest方法，参数是999  @0
- 执行repoTest模块的getListByProductTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('repo');
$table->id->range('1-5');
$table->product->range('1,1,2,1,3');
$table->name->range('repo1,repo2,repo3,repo4,repo5');
$table->SCM->range('Git{3},Gitlab{2}');
$table->deleted->range('0');
$table->gen(5);

su('admin');

$repoTest = new repoModelTest();

r($repoTest->getListByProductTest(1)) && p('1:name;2:name;4:name') && e('repo1;repo2;repo4');
r($repoTest->getListByProductTest(2, 'Git')) && p('3:name') && e('repo3');
r($repoTest->getListByProductTest(1, '', 2)) && p('1:name;2:name') && e('repo1;repo2');
r($repoTest->getListByProductTest(999)) && p() && e('0');
r($repoTest->getListByProductTest(0)) && p() && e('0');