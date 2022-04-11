#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/design.class.php';
su('admin');

/**

title=测试 designModel->getList();
cid=1
pid=1

默认输入查询 >> 0
不输入产品查询 >> 0
不输入项目查询 >> 31
项目和产品一起输入查询 >> 41
HLDS类型设计查询 >> HLDS
DDS类型设计查询 >> DDS
DBDS类型设计查询 >> DBDS
ADS类型设计查询 >> ADS
不存在类型设计查询 >> 0
查询条件查询设计 >> 这是一个设计1
不输入项目查询统计 >> 4
项目和产品一起输入查询统计 >> 4
HLDS类型设计查询统计 >> 1
DDS类型设计查询统计 >> 1
DBDS类型设计查询统计 >> 1
ADS类型设计查询统计 >> 1

*/
global $tester;
$design = $tester->loadModel('design');

$productIDList = array('0', '31');
$projectIDList = array('0', '41');
$types         = array('all', 'bySearch', 'HLDS', 'DDS', 'DBDS', 'ADS', 'QT');
$param         = array('0', '6');
$orderBy       = array('id_desc', 'name_desc');

r($design->getList($projectIDList[0], $productIDList[0], $types[0], $param[0], $orderBy[0]))        && p()            && e('0');            //默认输入查询
r($design->getList($projectIDList[1], $productIDList[0], $types[0], $param[0], $orderBy[0]))        && p()            && e('0');            //不输入产品查询
r($design->getList($projectIDList[0], $productIDList[1], $types[0], $param[0], $orderBy[0]))        && p('1:product') && e('31');           //不输入项目查询
r($design->getList($projectIDList[1], $productIDList[1], $types[0], $param[0], $orderBy[1]))        && p('1:project') && e('41');           //项目和产品一起输入查询
r($design->getList($projectIDList[1], $productIDList[1], $types[2], $param[0], $orderBy[0]))        && p('1:type')    && e('HLDS');         //HLDS类型设计查询
r($design->getList($projectIDList[1], $productIDList[1], $types[3], $param[0], $orderBy[0]))        && p('2:type')    && e('DDS');          //DDS类型设计查询
r($design->getList($projectIDList[1], $productIDList[1], $types[4], $param[0], $orderBy[0]))        && p('3:type')    && e('DBDS');         //DBDS类型设计查询
r($design->getList($projectIDList[1], $productIDList[1], $types[5], $param[0], $orderBy[0]))        && p('4:type')    && e('ADS');          //ADS类型设计查询
r($design->getList($projectIDList[1], $productIDList[1], $types[6], $param[0], $orderBy[0]))        && p()            && e('0');            //不存在类型设计查询
r($design->getList($projectIDList[1], $productIDList[1], $types[1], $param[1], $orderBy[0]))        && p('1:name')    && e('这是一个设计1');//查询条件查询设计

r(count($design->getList($projectIDList[0], $productIDList[1], $types[0], $param[0], $orderBy[0]))) && p()            && e('4');            //不输入项目查询统计
r(count($design->getList($projectIDList[1], $productIDList[1], $types[0], $param[0], $orderBy[1]))) && p()            && e('4');            //项目和产品一起输入查询统计
r(count($design->getList($projectIDList[1], $productIDList[1], $types[2], $param[0], $orderBy[0]))) && p()            && e('1');            //HLDS类型设计查询统计
r(count($design->getList($projectIDList[1], $productIDList[1], $types[3], $param[0], $orderBy[0]))) && p()            && e('1');            //DDS类型设计查询统计
r(count($design->getList($projectIDList[1], $productIDList[1], $types[4], $param[0], $orderBy[0]))) && p()            && e('1');            //DBDS类型设计查询统计
r(count($design->getList($projectIDList[1], $productIDList[1], $types[5], $param[0], $orderBy[0]))) && p()            && e('1');            //ADS类型设计查询统计