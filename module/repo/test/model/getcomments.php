#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getComments();
timeout=0
cid=0

- 空bugIDList @0
- 单个有效bugID @0
- 多个bugIDs @0
- 不存在的bugID @0
- 混合存在和不存在ID @0

*/

su('admin');

zendata('bug')->loadYaml('bug_getcommitsbyobject', false, 2)->gen(3);
zendata('action')->loadYaml('action_starttask', false, 2)->gen(5);

$repoTest = new repoModelTest();

r($repoTest->getCommentsTest(array())) && p() && e('0');        // 空bugIDList
r($repoTest->getCommentsTest(array(1))) && p() && e('0');       // 单个有效bugID
r($repoTest->getCommentsTest(array(1, 2))) && p() && e('0');    // 多个bugIDs
r($repoTest->getCommentsTest(array(999))) && p() && e('0');     // 不存在的bugID
r($repoTest->getCommentsTest(array(1, 999))) && p() && e('0');  // 混合存在和不存在ID