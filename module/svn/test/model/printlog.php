#!/usr/bin/env php
<?php

/**

title=svnModel->printLog();
timeout=0
cid=1

- 输出日志信息 @abc

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('repo')->config('repo')->gen(1);
su('admin');

global $tester;
$svn = $tester->loadModel('svn');

ob_start();
$svn->printLog('Log: abc');
$result = trim(ob_get_clean());
r(substr($result, -3)) && p() && e('abc'); // 输出日志信息