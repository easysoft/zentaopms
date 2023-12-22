#!/usr/bin/env php
<?php

/**

title=gitModel->setRepos();
timeout=0
cid=1

- 获取不到数据时，提示错误信息 @8
- 获取第一条git记录的name和SCM属性
 - 第1条的name属性 @unittest1
 - 第1条的SCM属性 @Gitlab
- 获取不到数据时，提示错误信息 @You must set one git repo.

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(10);
su('admin');

global $tester;
$git = $tester->loadModel('git');
$git->setRepos();

r(count($git->repos)) && p() && e('8'); // 获取不到数据时，提示错误信息
r($git->repos) && p('1:name,SCM') && e('unittest1,Gitlab'); // 获取第一条git记录的name和SCM属性

zdTable('repo')->gen(0);
dao::$cache = array();

ob_start();
$git->setRepos();
$result = ob_get_clean();
r($result) && p() && e('You must set one git repo.'); // 获取不到数据时，提示错误信息