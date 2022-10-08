#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/design.class.php';
su('admin');

/**

title=测试 designModel->getPairs();
cid=1
pid=1

查询不存在的产品 >> 0
查询不存在的类型 >> 0
查询HLDS设计 >> 1:这是一个设计1
查询DDS设计 >> 2:这是一个设计2
查询DBDS设计 >> 3:这是一个设计3
查询ADS设计 >> 4:这是一个设计4

*/
global $tester;
$design = $tester->loadModel('design');

$productIDList = array('0', '31');
$types         = array('all', 'HLDS', 'DDS', 'DBDS', 'ADS', '0');

r($design->getPairs($productIDList[0], $types[1])) && p()    && e('0');              //查询不存在的产品
r($design->getPairs($productIDList[1], $types[0])) && p()    && e('0');              //查询不存在的类型
r($design->getPairs($productIDList[1], $types[1])) && p('1') && e('1:这是一个设计1');//查询HLDS设计
r($design->getPairs($productIDList[1], $types[2])) && p('2') && e('2:这是一个设计2');//查询DDS设计
r($design->getPairs($productIDList[1], $types[3])) && p('3') && e('3:这是一个设计3');//查询DBDS设计
r($design->getPairs($productIDList[1], $types[4])) && p('4') && e('4:这是一个设计4');//查询ADS设计