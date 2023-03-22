#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/git.class.php';
su('admin');

/**

title=测试gitModel->setRepo();
cid=1
pid=1

设置错误版本库参数 >> ,,
设置正确版本库参数 >> 1,1,1

*/

$git = new gitTest();

$repo = new stdclass();
r($git->setRepo($repo)) && p("result,client,repoRoot") && e(",,");     // 设置错误版本库参数

$repo = $tester->dao->select('*')->from(TABLE_REPO)->limit(1)->fetch();
if(strtolower($repo->SCM) == 'gitlab') $repo = $tester->loadModel('repo')->processGitlab($repo);
r($git->setRepo($repo)) && p("result,client,repoRoot") && e("1,1,1");     // 设置正确版本库参数

