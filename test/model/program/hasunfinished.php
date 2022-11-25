#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::hasUnfinished();
cid=1
pid=1

获取项目集1下未完成的项目和项目集数量 >> 9
获取项目集2下未完成的项目和项目集数量 >> 7

*/

global $tester;
$tester->loadModel('program');
$program1 = $tester->program->getById(1);
$program2 = $tester->program->getById(2);

r($tester->program->hasUnfinished($program1)) && p() && e('9'); // 获取项目集1下未完成的项目和项目集数量
r($tester->program->hasUnfinished($program2)) && p() && e('7'); // 获取项目集2下未完成的项目和项目集数量