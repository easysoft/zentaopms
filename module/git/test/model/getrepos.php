#!/usr/bin/env php
<?php

/**

title=gitModel->getRepos();
timeout=0
cid=1

- 获取git代码库的数量 @8
- 获取第一条git记录的path属性属性1 @https://gitlabdev.qc.oop.cc/root/unittest11
- 获取不到数据时，提示错误信息 @You must set one git repo.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(10);
su('admin');

global $tester;
$git = $tester->loadModel('git');

$repos = $git->getRepos();
r(count($repos)) && p() && e('8'); // 获取git代码库的数量
r($repos) && p('1') && e('https://gitlabdev.qc.oop.cc/root/unittest11'); // 获取第一条git记录的path属性

zdTable('repo')->gen(0);
dao::$cache = array();

ob_start();
$git->getRepos();
$result = ob_get_clean();
r($result) && p() && e('You must set one git repo.'); // 获取不到数据时，提示错误信息