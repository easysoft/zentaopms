#!/usr/bin/env php
<?php
/**

title=buildModel->joinChildBuilds();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

$build = zdTable('build')->config('build');
$build->builds->range('[],`7,8,9`');
$build->gen(10);

su('admin');

global $tester;
$buildModel = $tester->loadModel('build');

$build = $buildModel->getByID(1);
r($buildModel->joinChildBuilds($build)) && p('allBugs', '|') && e('1,2'); // 无子版本

$build = $buildModel->getByID(2);
r($buildModel->joinChildBuilds($build)) && p('allBugs', '|') && e('4,5,19,20,22,23,25,26'); // 有子版本
