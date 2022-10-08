#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getTreeMenu();
cid=1
pid=1

查看返回的字符个数 >> 789

*/

global $tester;
$tester->loadModel('program');
$programs1 = $tester->program->getTreeMenu(1);

r(strlen($programs1)) && p() && e('789'); // 查看返回的字符个数