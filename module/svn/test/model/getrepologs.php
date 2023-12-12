#!/usr/bin/env php
<?php

/**

title=svnModel->getRepoLogs();
timeout=0
cid=1

- 查询提交ID为空的信息
 - 属性committer @user
 - 属性comment @+ Add unit_test dir.
- 查询提交ID为23的信息
 - 属性author @user
 - 属性msg @+ Add unit_test dir.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/svn.class.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

$svn = new svnTest();

r($svn->getRepoLogsTest(0))  && p('committer,comment') && e('user,+ Add unit_test dir.'); // 查询提交ID为空的信息
r($svn->getRepoLogsTest(23)) && p('author,msg')        && e('user,+ Add unit_test dir.'); // 查询提交ID为23的信息