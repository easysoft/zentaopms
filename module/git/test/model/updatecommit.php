#!/usr/bin/env php
<?php

/**

title=gitModel->updateCommit();
timeout=0
cid=1

- 未同步的代码库 @0
- 已同步的代码库
 - 属性id @1
 - 属性repo @1
 - 属性commit @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/git.unittest.class.php';

$repo = zenData('repo')->loadYaml('repo');
$repo->path->range('https://gitlabdev.qc.oop.cc/root/unittest1');
$repo->gen(3);
zenData('repohistory')->loadYaml('repohistory')->gen(1);
su('admin');

$git = new gitTest();

r($git->updateCommitTest(2)) && p() && e('0'); // 未同步的代码库

r($git->updateCommitTest(1)) && p('id,repo,commit') && e('1,1,1'); // 已同步的代码库