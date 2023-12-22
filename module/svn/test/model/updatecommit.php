#!/usr/bin/env php
<?php

/**

title=svnModel->updateCommit();
timeout=0
cid=1

- 未同步的代码库 @0
- 已同步的代码库
 - 属性id @3
 - 属性repo @1
 - 属性commit @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/svn.class.php';

$repo = zdTable('repo')->config('repo');
$repo->path->range('https://svn.qc.oop.cc/svn/unittest');
$repo->gen(3);
su('admin');

$svn = new svnTest();

r($svn->updateCommitTest(2)) && p() && e('0'); // 未同步的代码库

r($svn->updateCommitTest(1)) && p('id,repo,commit') && e('3,1,3'); // 已同步的代码库