#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->getCommits();
timeout=0
cid=0

- 有效repoID根路径 >> 返回commits数组
- 指定子路径 >> 返回commits数组
- 指定revision >> 返回commits数组
- repoID=0 >> 返回空数组
- 不存在的repoID >> 返回空数组

*/

su('admin');

zendata('repo')->loadYaml('repo_getcommits', false, 2)->gen(2);

$zenTest = new repoZenTest();

r($zenTest->getCommitsTest(1)) && p() && e(array());              // 有效repoID根路径
r($zenTest->getCommitsTest(1, '/src')) && p() && e(array());      // 指定子路径
r($zenTest->getCommitsTest(1, '/', 'HEAD', 'dir', 0)) && p() && e(array()); // 指定revision
r($zenTest->getCommitsTest(0)) && p() && e(array());              // repoID=0
r($zenTest->getCommitsTest(999)) && p() && e(array());            // 不存在的repoID
