#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/git.class.php';
su('admin');

/**

title=测试gitModel->getRepoTags();
cid=1
pid=1

10.0.1.161:51080')->where('id')->eq(1)->exec(); >> id

*/

$git = new gitTest();

/* Fix error Gitlab url. */
$tester->dao->update(TABLE_PIPELINE)->set('url')->eq('http://10.0.1.161:51080')->where('id')->eq(1)->exec();

$repo = $tester->dao->select('*')->from(TABLE_REPO)->limit(1)->fetch();
if(strtolower($repo->SCM) == 'gitlab') $repo = $tester->loadModel('repo')->processGitlab($repo);
r($git->getRepoTags($repo)) && p() && e(1);     // 获取版本库的tags

$repo = new stdclass();
r($git->getRepoTags($repo)) && p() && e(0);    // 使用空数据

$repo = new stdclass();
$repo->client = '';
$repo->path   = '';
r($git->getRepoTags($repo)) && p() && e(0);    // 使用错误的版本库

