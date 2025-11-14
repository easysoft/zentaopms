#!/usr/bin/env php
<?php

/**

title=测试 repoModel::isClickable();
timeout=0
cid=18085

- 执行repoTest模块的isClickableTest方法，参数是$repo1, 'execJob'  @0
- 执行repoTest模块的isClickableTest方法，参数是$repo2, 'execJob'  @1
- 执行repoTest模块的isClickableTest方法，参数是$repo3, 'reportView'  @0
- 执行repoTest模块的isClickableTest方法，参数是$repo4, 'reportView'  @1
- 执行repoTest模块的isClickableTest方法，参数是$repo5, 'edit'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

$repo = zenData('repo');
$repo->id->range('1-5');
$repo->name->range('repo1,repo2,repo3,repo4,repo5');
$repo->SCM->range('Git');
$repo->product->range('1');
$repo->acl->range('open');
$repo->gen(5);

su('admin');

$repoTest = new repoTest();

$repo1 = new stdclass();
$repo1->exec = 'disabled';

$repo2 = new stdclass();
$repo2->exec = '';

$repo3 = new stdclass();
$repo3->report = 'disabled';

$repo4 = new stdclass();
$repo4->report = '';

$repo5 = new stdclass();

r($repoTest->isClickableTest($repo1, 'execJob'))    && p() && e('0');
r($repoTest->isClickableTest($repo2, 'execJob'))    && p() && e('1');
r($repoTest->isClickableTest($repo3, 'reportView')) && p() && e('0');
r($repoTest->isClickableTest($repo4, 'reportView')) && p() && e('1');
r($repoTest->isClickableTest($repo5, 'edit'))       && p() && e('1');