#!/usr/bin/env php
<?php
/**

title=buildModel->buildOperateMenu();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->config('build')->gen(10);
zdTable('product')->config('product')->gen(10);
zdTable('project')->config('execution')->gen(10);
su('admin');

global $tester;
$buildModel = $tester->loadModel('build');

$build = $buildModel->getByID(1);
$menus = $buildModel->buildOperateMenu($build);
r($menus)        && p('0:text') && e('编辑版本'); // 检查是否有编辑链接。
r(count($menus)) && p()         && e('2');        // 检查链接数量。
