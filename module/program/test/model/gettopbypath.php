#!/usr/bin/env php
<?php
/**

title=测试 programModel::getTopByPath();
cid=0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('program')->gen(15);

su('admin');

global $tester;
$tester->loadModel('program');
$path1 = ',2,3,4,';
$path2 = '100,101';

r($tester->program->getTopByPath($path1)) && p() && e('2');   // 传入一个path，返回最顶级path
r($tester->program->getTopByPath($path2)) && p() && e('100'); // 传入一个path，返回最顶级path
