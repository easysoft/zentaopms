#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/git.class.php';
su('admin');

/**

title=测试gitModel->setRepo();
timeout=0
cid=1

- 设置错误版本库参数
 - 属性result @
 - 属性client @
 - 属性repoRoot @
- 设置正确版本库参数
 - 属性result @1
 - 属性client @1
 - 属性repoRoot @1

*/

$git = new gitTest();

$repo = new stdclass();
r($git->setRepo($repo)) && p("result,client,repoRoot") && e(",,");     // 设置错误版本库参数

$repo = $tester->dao->select('*')->from(TABLE_REPO)->limit(1)->fetch();
if(strtolower($repo->SCM) == 'gitlab') $repo = $tester->loadModel('repo')->processGitlab($repo);
r($git->setRepo($repo)) && p("result,client,repoRoot") && e("1,1,1");     // 设置正确版本库参数