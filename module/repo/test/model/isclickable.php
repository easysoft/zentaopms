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
- 执行repoTest模块的isClickableTest方法，参数是$repoMirror, 'scanExec'  @0
- 执行repoTest模块的isClickableTest方法，参数是$repoMirror, 'scanIssue'  @0
- 执行repoTest模块的isClickableTest方法，参数是$repo5, 'scanExec'  @1
- 执行repoTest模块的isClickableTest方法，参数是$repo5, 'scanIssue'  @1
- 执行repoTest模块的isClickableTest方法，参数是$repoInSpace, 'edit'  @1
- 执行repoTest模块的isClickableTest方法，参数是$repoInSpace, 'delete'  @1
- 执行repoTest模块的isClickableTest方法，参数是$repoInSpace, 'scanExec'  @1
- 执行repoTest模块的isClickableTest方法，参数是$repoInSpace, 'scanIssue'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$repoTest = new repoModelTest();

$repo1 = new stdclass();
$repo1->exec = 'disabled';

$repo2 = new stdclass();
$repo2->exec = '';

$repo3 = new stdclass();
$repo3->report = 'disabled';

$repo4 = new stdclass();
$repo4->report = '';

$repo5 = new stdclass();

$repoMirror = new stdclass();
$repoMirror->mirror = 1;

r($repoTest->isClickableTest($repo1, 'execJob'))         && p() && e('0');
r($repoTest->isClickableTest($repo2, 'execJob'))         && p() && e('1');
r($repoTest->isClickableTest($repo3, 'reportView'))      && p() && e('0');
r($repoTest->isClickableTest($repo4, 'reportView'))      && p() && e('1');
r($repoTest->isClickableTest($repo5, 'edit'))            && p() && e('1');
r($repoTest->isClickableTest($repoMirror, 'scanExec'))   && p() && e('0');
r($repoTest->isClickableTest($repoMirror, 'scanIssue'))  && p() && e('0');
r($repoTest->isClickableTest($repo5, 'scanExec'))        && p() && e('1');
r($repoTest->isClickableTest($repo5, 'scanIssue'))       && p() && e('1');

/* GitFox独立版下repo/codescan的navGroup不再是devops，空间权限清单仍需包含它们，否则代码库列表的行内按钮会全部置灰。 */
$config->vision = 'gitfox';
include dirname(__FILE__, 5) . '/extension/gitfox/common/ext/lang/gitfoxmenu.php';

$spaceModel = $app->loadTarget('space', '', 'model');
repoModel::$maintainSpacePrivs = array(1 => $spaceModel->getDevOpsAllPrivs());

$repoInSpace = new stdclass();
$repoInSpace->spaceID = 1;
$repoInSpace->mirror  = 0;

r($repoTest->isClickableTest($repoInSpace, 'edit'))      && p() && e('1');
r($repoTest->isClickableTest($repoInSpace, 'delete'))    && p() && e('1');
r($repoTest->isClickableTest($repoInSpace, 'scanExec'))  && p() && e('1');
r($repoTest->isClickableTest($repoInSpace, 'scanIssue')) && p() && e('1');
