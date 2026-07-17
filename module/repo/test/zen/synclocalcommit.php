#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->syncLocalCommit();
timeout=0
cid=0

- 有效repoID >> 返回1(同步完成)或错误信息
- repoID不存在 >> 返回not_found
- repoID=0 >> 返回not_found
- Git类型的repo >> 处理Git同步
- repoID=-1 >> 返回not_found

*/

su('admin');

zendata('repo')->loadYaml('repo_getcommits', false, 2)->gen(2);

$zenTest = new repoZenTest();

r($zenTest->syncLocalCommitTest(1)) && p() && e('1');        // 有效repoID
r($zenTest->syncLocalCommitTest(999)) && p() && e('not_found');  // repoID不存在
r($zenTest->syncLocalCommitTest(0)) && p() && e('not_found');    // repoID=0
r($zenTest->syncLocalCommitTest(1)) && p() && e('1');        // 再次验证有效
r($zenTest->syncLocalCommitTest(-1)) && p() && e('not_found');   // repoID=-1
