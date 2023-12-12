#!/usr/bin/env php
<?php
/**
title=svnModel->run();
timeout=0
cid=1

- 更新svn提交信息到禅道，检查第二条记录是否正确
 - 属性commit @2
 - 属性comment @+ Add secondary file.
 - 属性committer @user

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/svn.class.php';

zdTable('job')->gen(0);
zdTable('repo')->config('repo')->gen(1);
zdTable('repofiles')->config('repofiles')->gen(1);
zdTable('repohistory')->config('repohistory')->gen(1);
su('admin');

$svn = new svnTest();

r($svn->runTest()) && p('commit,comment,committer') && e('2,+ Add secondary file.,user'); // 更新svn提交信息到禅道，检查第二条记录是否正确
