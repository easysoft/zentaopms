#!/usr/bin/env php
<?php

/**

title=svnModel->setRepos();
timeout=0
cid=1

- 获取不到数据时，提示错误信息 @5
- 获取第一条svn记录的name和SCM属性
 - 第1条的name属性 @svn-1
 - 第1条的SCM属性 @Subversion
- 获取不到数据时，提示错误信息 @You must set one svn repo.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('repo')->loadYaml('repo')->gen(10);
su('admin');

global $tester;
$svn = $tester->loadModel('svn');
$svn->setRepos();

r(count($svn->repos)) && p() && e('5'); // 获取不到数据时，提示错误信息
r($svn->repos) && p('1:name,SCM') && e('svn-1,Subversion'); // 获取第一条svn记录的name和SCM属性

zenData('repo')->gen(0);

ob_start();
$svn->setRepos();
$result = ob_get_clean();
r($result) && p() && e('You must set one svn repo.'); // 获取不到数据时，提示错误信息
