#!/usr/bin/env php
<?php
/**
title=测试 pivotTao->getGroupsByDimensionAndPath();
cid=1
pid=1

测试维度为1的第一个分组id   >> 1
测试维度为2的第一个分组id   >> 4
测试维度为3的第一个分组id   >> 7
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

zdTable('module')->config('module_pivot')->gen(9);

$pivot = new pivotTest();
$dimensionIDList = array(1,2,3);

r($pivot->getFirstGroup($dimensionIDList[0])) && p('') && e('1');    //获取维度为1的第一个分组id
r($pivot->getFirstGroup($dimensionIDList[1])) && p('') && e('4');    //获取维度为2的第一个分组id
r($pivot->getFirstGroup($dimensionIDList[2])) && p('') && e('7');    //获取维度为3的第一个分组id
