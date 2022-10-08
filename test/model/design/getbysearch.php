#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 designModel->getBySearch();
cid=1
pid=1

不输入产品和项目查询 >> 0
输入项目查询 >> 0
输入产品查询 >> 0
正常查询条件查询 >> 这是一个设计1

*/
global $tester;
$design = $tester->loadModel('design');

$productIDList = array('0', '31');
$projectIDList = array('0', '41');
$queryID       = array('0', '6');
$orderBy       = array('id_desc', 'name_desc');

r($design->getBySearch($projectIDList[0], $productIDList[0], $queryID[0], $orderBy[0])) && p()         && e('0');            //不输入产品和项目查询
r($design->getBySearch($projectIDList[1], $productIDList[0], $queryID[0], $orderBy[0])) && p()         && e('0');            //输入项目查询
r($design->getBySearch($projectIDList[0], $productIDList[1], $queryID[0], $orderBy[0])) && p()         && e('0');            //输入产品查询
r($design->getBySearch($projectIDList[1], $productIDList[1], $queryID[1], $orderBy[1])) && p('1:name') && e('这是一个设计1');//正常查询条件查询