#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->getTreeMenu();
cid=1
pid=1

查看获取到的menu option的字符串数量 >> 1596

*/

global $tester;
$tester->loadModel('project');

$lastMenu = $tester->project->getTreeMenu(0, array('projectmodel', 'createManageLink'));

r(strlen($lastMenu)) && p() && e('1596');  // 查看获取到的menu option的字符串数量