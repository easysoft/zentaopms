#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(5);
su('admin');

zenData('product')->loadYaml('product')->gen(30);

/**

title=测试 programModel::getProductByProgram();
timeout=0
cid=17692

*/

$programIdList = array(array(), array(1, 2, 3));

global $tester;
$tester->loadModel('program');
r(current($tester->program->getProductByProgram($programIdList[0]))) && p('0:program,name') && e('1,产品1'); // 获取系统内所有项目集下的产品信息
r(current($tester->program->getProductByProgram($programIdList[1]))) && p('0:program,name') && e('1,产品1'); // 获取系统内所有项目集1、项目集2、项目集3下的产品信息
r(count($tester->program->getProductByProgram($programIdList[0])))   && p()                 && e('10');      // 获取系统内所有项目集下的产品数量
r(count($tester->program->getProductByProgram($programIdList[1])))   && p()                 && e('3');       // 获取系统内所有项目集1、项目集2、项目集3下的产品数量
