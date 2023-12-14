#!/usr/bin/env php
<?php

/**

title=svnModel->getRepos();
timeout=0
cid=1

- 获取不到数据时，提示错误信息 @5
- 获取第一条svn记录的path属性属性1 @https://svn.qc.oop.cc/svn/unittest1
- 获取不到数据时，提示错误信息 @You must set one svn repo.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(10);
su('admin');

global $tester;
$svn = $tester->loadModel('svn');
$repos = $svn->getRepos();

r(count($repos)) && p() && e('5'); // 获取不到数据时，提示错误信息
r($repos) && p('1') && e('https://svn.qc.oop.cc/svn/unittest1'); // 获取第一条svn记录的path属性

zdTable('repo')->gen(0);
dao::$cache = array();

ob_start();
$svn->getRepos();
$result = ob_get_clean();
r($result) && p() && e('You must set one svn repo.'); // 获取不到数据时，提示错误信息