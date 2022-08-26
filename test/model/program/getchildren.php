#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel:: getChildren();
cid=1
pid=1

通过id查找id=1的子项目集个数 >> 9
通过id查找id=22的子项目集个数 >> 7
通过id查找id=221的子项目集个数 >> 0

*/

global $tester;
$tester->loadModel('program');

r($tester->program->getChildren(1))   && p() && e('9'); // 通过id查找id=1的子项目集个数
r($tester->program->getChildren(22))  && p() && e('7'); // 通过id查找id=22的子项目集个数
r($tester->program->getChildren(221)) && p() && e('0'); // 通过id查找id=221的子项目集个数