#!/usr/bin/env php
<?php

/**

title=测试 solutionModel->saveLog();
timeout=0
cid=1

- 没有日志信息 @1
- 有日志信息 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/solution.class.php';

$solutionModel = new solutionTest();

r($solutionModel->saveLogTest(''))     && p() && e('1');  // 没有日志信息
r($solutionModel->saveLogTest('test')) && p() && e('5');  // 有日志信息
