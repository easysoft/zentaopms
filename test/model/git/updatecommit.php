#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/git.class.php';
su('admin');

/**

title=测试gitModel->updatecommit();
cid=1
pid=1



*/

$git = new gitTest();

$repo = $tester->dao->select('*')->from(TABLE_REPO)->limit(1)->fetch();
if(strtolower($repo->SCM) == 'gitlab') $repo = $tester->loadModel('repo')->processGitlab($repo);
r($git->updateCommit($repo)) && p() && e(1);     // 测试正常的版本库

$repo = new stdclass();
r($git->updateCommit($repo)) && p() && e(0);    // 测试空的版本库

