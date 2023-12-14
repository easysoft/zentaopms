#!/usr/bin/env php
<?php

/**

title=svnModel->getRepoLogs();
timeout=0
cid=1

- 查询提交ID为空的信息
 - 属性committer @admin
 - 属性comment @+ Add file.
- 查询提交ID为1的信息
 - 属性author @admin
 - 属性msg @+ Add file.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/svn.class.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

$svn = new svnTest();

r($svn->getRepoLogsTest(0)) && p('committer,comment') && e('admin,+ Add file.'); // 查询提交ID为空的信息
r($svn->getRepoLogsTest(1)) && p('author,msg')        && e('admin,+ Add file.'); // 查询提交ID为1的信息