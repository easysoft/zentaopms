#!/usr/bin/env php
<?php
/**
title=测试 pivotTao->getGroupsByDimensionAndPath();
cid=1
pid=1

测试获取dimensionID为1，path为,3的模块信息  >> 3,这是一个模块3
测试获取dimensionID为2，path为,6的模块信息  >> 6,这是一个模块6
测试获取dimensionID为3，path为,9的模块信息  >> 9,这是一个模块9
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

zdTable('module')->config('module')->gen(9);

$pivot = new pivotTest();
$dimensionIDList = array(1,2,3);
$pathList        = array(',3',',6',',9');

r($pivot->getGroupsByDimensionAndPath($dimensionIDList[0], $pathList[0])) && p('0:id,name') && e('3,这是一个模块3');    //测试获取dimensionID为1，path为,3的模块信息
r($pivot->getGroupsByDimensionAndPath($dimensionIDList[1], $pathList[1])) && p('0:id,name') && e('6,这是一个模块6');    //测试获取dimensionID为2，path为,6的模块信息
r($pivot->getGroupsByDimensionAndPath($dimensionIDList[2], $pathList[2])) && p('0:id,name') && e('9,这是一个模块9');    //测试获取dimensionID为3，path为,9的模块信息
