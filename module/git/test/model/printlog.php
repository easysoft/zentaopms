#!/usr/bin/env php
<?php

/**

title=gitModel->printLog();
timeout=0
cid=1

- 输出日志信息 @abc

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

global $tester;
$git = $tester->loadModel('git');

ob_start();
$git->printLog('Log: abc');
$result = trim(ob_get_clean());
r(substr($result, -3)) && p() && e('abc'); // 输出日志信息