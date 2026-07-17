#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->getGitlabProjectsByApi();
timeout=0
cid=0

- 有效gitlabID >> 返回项目列表或空数组
- gitlabID=0 >> 返回空数组
- 无效token >> 返回空数组
- gitlabID=-1 >> 返回空数组
- 大gitlabID >> 返回空数组

*/

su('admin');

$zenTest = new repoZenTest();

r($zenTest->getGitlabProjectsByApiTest(1)) && p() && e(array());    // 有效gitlabID
r($zenTest->getGitlabProjectsByApiTest(0)) && p() && e(array());    // gitlabID=0
r($zenTest->getGitlabProjectsByApiTest(0)) && p() && e(array());    // 无效token
r($zenTest->getGitlabProjectsByApiTest(-1)) && p() && e(array());   // gitlabID=-1
r($zenTest->getGitlabProjectsByApiTest(999)) && p() && e(array());  // 大gitlabID
