#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getBugsByRepo();
timeout=0
cid=0

- repoID=0 默认浏览 @5
- repoID=1 @0
- browseType=assigntome @0
- browseType=openedbyme @0
- 带executionID @0

*/

su('admin');

zendata('bug')->loadYaml('bug_getcommitsbyobject', false, 2)->gen(5);

$repoTest = new repoModelTest();

r($repoTest->getBugsByRepoTest()) && p() && e('5');              // repoID=0 默认浏览
r($repoTest->getBugsByRepoTest(1)) && p() && e('0');             // repoID=1
r($repoTest->getBugsByRepoTest(1, 'assigntome')) && p() && e('0');  // browseType=assigntome
r($repoTest->getBugsByRepoTest(1, 'openedbyme')) && p() && e('0');  // browseType=openedbyme
r($repoTest->getBugsByRepoTest(1, '', 1)) && p() && e('0');        // 带executionID