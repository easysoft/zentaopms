#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->checkDeleteError();
timeout=0
cid=0

- 有效repoID >> 返回空字符串或错误信息
- repoID=0 >> 返回字符串
- repoID=-1 >> 返回字符串
- 不存在的repoID >> 返回字符串
- 大repoID >> 返回字符串

*/

su('admin');

zendata('repo')->loadYaml('repo_getcommits', false, 2)->gen(2);

$zenTest = new repoZenTest();

r($zenTest->checkDeleteErrorTest(1)) && p() && e('');        // 有效repoID
r($zenTest->checkDeleteErrorTest(0)) && p() && e('');        // repoID=0
r($zenTest->checkDeleteErrorTest(-1)) && p() && e('');       // repoID=-1
r($zenTest->checkDeleteErrorTest(999)) && p() && e('');      // 不存在的repoID
r($zenTest->checkDeleteErrorTest(10000)) && p() && e('');    // 大repoID
