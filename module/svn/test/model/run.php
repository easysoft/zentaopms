#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/svn.class.php';

zdTable('job')->gen(0);
zdTable('repo')->config('repo')->gen(1);
zdTable('repofiles')->config('repofiles')->gen(1);
zdTable('repohistory')->config('repohistory')->gen(1);
su('admin');

/**

title=svnModel->run();
timeout=0
cid=1

*/

$svn = new svnTest();

r($svn->runTest()) && p('commit,comment,committer') && e('2,+ Add secondary file.,user');

