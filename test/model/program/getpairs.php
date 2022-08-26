#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getPairs();
cid=1
pid=1

获取项目集个数 >> 6
获取项目集个数 >> 10
获取项目集id为9的名称 >> 项目集9

*/

global $tester;
$tester->loadModel('program');
$programs1 = $tester->program->getPairs();
$programs2 = $tester->program->getPairs('true');

r(count($programs1)) && p()     && e('6');       // 获取项目集个数
r(count($programs2)) && p()     && e('10');      // 获取项目集个数
r($programs1)        && p('9')  && e('项目集9'); // 获取项目集id为9的名称
r($programs2)        && p('11') && e('');        // 获取不存在的项目集