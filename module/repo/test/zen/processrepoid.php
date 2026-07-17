#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->processRepoID();
timeout=0
cid=0

- repoID=1 objectID=0 >> 返回repoID
- repoID=0 objectID=0 >> 使用session repoID
- repoID=0 objectID=1 >> 带objectID
- 带scmList >> 返回repoID
- repoID=-1 >> 返回处理后的ID

*/

su('admin');

zendata('repo')->loadYaml('repo_getcommits', false, 2)->gen(2);

$zenTest = new repoZenTest();

r($zenTest->processRepoIDTest(1, 0)) && p() && e(1);                 // repoID=1 objectID=0
r($zenTest->processRepoIDTest(0, 0)) && p() && e(0);                 // repoID=0 objectID=0
r($zenTest->processRepoIDTest(0, 1)) && p() && e(0);                 // repoID=0 objectID=1
r($zenTest->processRepoIDTest(1, 0, array('Gitlab'))) && p() && e(1);  // 带scmList
r($zenTest->processRepoIDTest(-1, 0)) && p() && e(-1);               // repoID=-1
