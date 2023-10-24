#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 projectModel->start();
cid=1
pid=1

延期ID为81的项目，查看延期后的日期 >> 2023-07-01
延期ID为0的项目，返回空 >> 0

*/

global $tester;
$tester->loadModel('project');
$_POST['end'] = '2023-07-01';

$changes1 = $tester->project->putoff(81);
$changes2 = $tester->project->putoff(0);

r($changes1[0]) && p('new') && e('2023-07-01'); // 延期ID为81的项目，查看延期后的日期
r($changes2)    && p()      && e('0');          // 延期ID为0的项目，返回空
