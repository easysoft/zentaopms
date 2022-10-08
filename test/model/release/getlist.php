#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/release.class.php';
su('admin');

/**

title=测试 releaseModel->getList();
cid=1
pid=1

branch为'all'并且type为'all'时数据查询 >> 1,产品1正常的发布1
branch为'all'并且type为'normal'时数据查询 >> 5,产品1正常的里程碑发布5
branch为'all'并且type为'terminate'时数据查询 >> 1,产品1正常的发布1
branch为'all'并且type为空时数据查询 >> 0
branch为0并且type为'all'时数据查询 >> 1,产品1正常的发布1
branch为0并且type为'normal'时数据查询 >> 3,产品1正常的发布3
branch为0并且type为'terminate'时数据查询 >> 1,产品1正常的发布1
branch为0并且type为空时数据查询 >> 0
branch为1并且type为'all'时数据查询 >> 0
branch为1并且type为'normal'时数据查询 >> 0
branch为1并且type为'terminate'时数据查询 >> 0
branch为1并且type为空时数据查询 >> 0
branch为2并且type为'all'时数据查询 >> 0
branch为2并且type为'normal'时数据查询 >> 0
branch为2并且type为'terminate'时数据查询 >> 0
branch为2并且type为空时数据查询 >> 0
branch为空并且type为'all'时数据查询 >> 1,产品1正常的发布1
branch为空并且type为'normal'时数据查询 >> ,产品1正常的发布3
branch为空并且type为'terminate'时数据查询 >> 1,产品1正常的发布1
branch为空并且type为空时数据查询 >> 0
产品ID为空数据查询 >> 1,产品1正常的发布1
产品ID不存在数据查询 >> 0

*/

$productID   = ['','1','10000'];
$branchArray = ['all','0','1','2',''];
$typeArray   = ['all','normal','terminate',''];

$release   = new releaseTest();

r($release->getListTest($productID[1], $branchArray[0], $typeArray[0])) && p('0:id,name') && e('1,产品1正常的发布1');               //branch为'all'并且type为'all'时数据查询
r($release->getListTest($productID[1], $branchArray[0], $typeArray[1])) && p('1:id,name') && e('5,产品1正常的里程碑发布5');               //branch为'all'并且type为'normal'时数据查询
r($release->getListTest($productID[1], $branchArray[0], $typeArray[2])) && p('0:id,name') && e('1,产品1正常的发布1'); //branch为'all'并且type为'terminate'时数据查询
r($release->getListTest($productID[1], $branchArray[0], $typeArray[3])) && p()            && e('0');                                     //branch为'all'并且type为空时数据查询
r($release->getListTest($productID[1], $branchArray[1], $typeArray[0])) && p('0:id,name') && e('1,产品1正常的发布1');               //branch为0并且type为'all'时数据查询
r($release->getListTest($productID[1], $branchArray[1], $typeArray[1])) && p('0:id,name') && e('3,产品1正常的发布3');               //branch为0并且type为'normal'时数据查询
r($release->getListTest($productID[1], $branchArray[1], $typeArray[2])) && p('0:id,name') && e('1,产品1正常的发布1'); //branch为0并且type为'terminate'时数据查询
r($release->getListTest($productID[1], $branchArray[1], $typeArray[3])) && p()            && e('0');                                     //branch为0并且type为空时数据查询
r($release->getListTest($productID[1], $branchArray[2], $typeArray[0])) && p()            && e('0');                                     //branch为1并且type为'all'时数据查询
r($release->getListTest($productID[1], $branchArray[2], $typeArray[1])) && p()            && e('0');                                     //branch为1并且type为'normal'时数据查询
r($release->getListTest($productID[1], $branchArray[2], $typeArray[2])) && p()            && e('0');                                     //branch为1并且type为'terminate'时数据查询
r($release->getListTest($productID[1], $branchArray[2], $typeArray[3])) && p()            && e('0');                                     //branch为1并且type为空时数据查询
r($release->getListTest($productID[1], $branchArray[3], $typeArray[0])) && p()            && e('0');                                     //branch为2并且type为'all'时数据查询
r($release->getListTest($productID[1], $branchArray[3], $typeArray[1])) && p()            && e('0');                                     //branch为2并且type为'normal'时数据查询
r($release->getListTest($productID[1], $branchArray[3], $typeArray[2])) && p()            && e('0');                                     //branch为2并且type为'terminate'时数据查询
r($release->getListTest($productID[1], $branchArray[3], $typeArray[3])) && p()            && e('0');                                     //branch为2并且type为空时数据查询
r($release->getListTest($productID[1], $branchArray[4], $typeArray[0])) && p('0:id,name') && e('1,产品1正常的发布1');               //branch为空并且type为'all'时数据查询
r($release->getListTest($productID[1], $branchArray[4], $typeArray[1])) && p('0:id,name') && e('3,产品1正常的发布3');               //branch为空并且type为'normal'时数据查询
r($release->getListTest($productID[1], $branchArray[4], $typeArray[2])) && p('0:id,name') && e('1,产品1正常的发布1'); //branch为空并且type为'terminate'时数据查询
r($release->getListTest($productID[1], $branchArray[4], $typeArray[3])) && p()            && e('0');                                     //branch为空并且type为空时数据查询
r($release->getListTest($productID[0], $branchArray[0], $typeArray[0])) && p('0:id,name') && e('1,产品1正常的发布1');               //产品ID为空数据查询
r($release->getListTest($productID[2], $branchArray[0], $typeArray[0])) && p()            && e('0');                                     //产品ID不存在数据查询
