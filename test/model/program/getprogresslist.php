#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModee::getProgressList();
cid=1
pid=1

获取项目和项目集的个数 >> 120
获取id=1的项目的进度 >> 41
获取id=11的项目集的进度 >> 45

*/

global $tester;
$tester->loadModel('program');
$progressList = $tester->program->getProgressList();

r(count($progressList)) && p()     && e('120');  // 获取项目和项目集的个数
r($progressList)        && p('1')  && e('41');   // 获取id=1的项目的进度
r($progressList)        && p('11') && e('45');   // 获取id=11的项目集的进度