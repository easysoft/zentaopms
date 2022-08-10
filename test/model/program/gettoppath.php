#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getTopPairs();
cid=1
pid=1

传入一个path，返回最顶级path >> 2
传入一个path，返回最顶级path >> 100

*/

global $tester;
$tester->loadModel('program');
$path1 = ',2,3,4,';
$path2 = '100,101';

r($tester->program->getTopByPath($path1)) && p() && e('2');   // 传入一个path，返回最顶级path
r($tester->program->getTopByPath($path2)) && p() && e('100'); // 传入一个path，返回最顶级path