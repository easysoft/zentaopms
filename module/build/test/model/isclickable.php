#!/usr/bin/env php
<?php
/**

title=buildModel->isClickable();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->gen(2);

$build = new buildTest('admin');
r($build->isClickable('bug'))             && p() && e('1'); // 判断bug模块操作
r($build->isClickable('testtask', false)) && p() && e('1'); // 判断testtask模块操作
r($build->isClickable('testtask', true))  && p() && e('0'); // 判断testtask模块操作
