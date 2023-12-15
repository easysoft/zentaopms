#!/usr/bin/env php
<?php
/**

title=测试 designModel->linkCommit();
cid=1

- 测试空数据 @0
- 测试designID为空的情况 @0
- 测试repoID为空的情况 @0
- 测试提交记录为空的情况 @0
- 测试正常情况 @1;2;3
- 测试designID不存在的情况 @0
- 测试repoID不存在的情况 @1;2;3
- 测试提交记录不存在的情况 @1;2;3;
- 测试传入的参数都不存在的情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/design.class.php';

zdTable('file')->gen(0);
zdTable('repo')->config('repo')->gen(1);
zdTable('repohistory')->config('repohistory')->gen(3);
zdTable('design')->config('design')->gen(1);
zdTable('relation')->gen(0);

$designs      = array(0, 1, 2);
$repos        = array(0, 1, 2);
$revisions[0] = array();
$revisions[1] = array(1, 2, 3);
$revisions[2] = array(5, 6, 7, 8);

$designTester = new designTest();
r($designTester->linkCommitTest($designs[0], $repos[0], $revisions[0])) && p() && e('0');      // 测试空数据
r($designTester->linkCommitTest($designs[0], $repos[1], $revisions[1])) && p() && e('0');      // 测试designID为空的情况
r($designTester->linkCommitTest($designs[1], $repos[0], $revisions[1])) && p() && e('0');      // 测试repoID为空的情况
r($designTester->linkCommitTest($designs[1], $repos[1], $revisions[0])) && p() && e('0');      // 测试提交记录为空的情况
r($designTester->linkCommitTest($designs[1], $repos[1], $revisions[1])) && p() && e('1;2;3');  // 测试正常情况
r($designTester->linkCommitTest($designs[2], $repos[1], $revisions[1])) && p() && e('0');      // 测试designID不存在的情况
r($designTester->linkCommitTest($designs[1], $repos[2], $revisions[1])) && p() && e('1;2;3');  // 测试repoID不存在的情况
r($designTester->linkCommitTest($designs[1], $repos[1], $revisions[2])) && p() && e('1;2;3;'); // 测试提交记录不存在的情况
r($designTester->linkCommitTest($designs[2], $repos[2], $revisions[2])) && p() && e('0');      // 测试传入的参数都不存在的情况
