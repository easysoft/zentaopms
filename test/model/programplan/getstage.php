#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/programplan.class.php';
su('admin');

/**

title=测试 programplanModel->getStage();
cid=1
pid=1

测试获取执行41 产品21 browse all的计划键值对 >> ,阶段31,阶段121,阶段211,阶段301,阶段391,阶段481,阶段571,子阶段1
测试获取执行41 产品21 browse parent的计划键值对 >> ,阶段31,阶段121,阶段211,阶段301,阶段391,阶段481,阶段571
测试获取执行41 产品21 browse all id desc的计划键值对 >> ,子阶段1,阶段571,阶段481,阶段391,阶段301,阶段211,阶段121,阶段31
测试获取执行42 产品22 browse all的计划键值对 >> ,阶段32,阶段122,阶段212,阶段302,阶段392,阶段482,阶段572,子阶段2
测试获取执行42 产品22 browse parent的计划键值对 >> ,阶段32,阶段122,阶段212,阶段302,阶段392,阶段482,阶段572
测试获取执行43 产品23 browse all的计划键值对 >> ,阶段33,阶段123,阶段213,阶段303,阶段393,阶段483,阶段573,子阶段3
测试获取执行43 产品23 browse parent的计划键值对 >> ,阶段33,阶段123,阶段213,阶段303,阶段393,阶段483,阶段573
测试获取执行44 产品24 browse all的计划键值对 >> ,阶段34,阶段124,阶段214,阶段304,阶段394,阶段484,阶段574,子阶段4
测试获取执行44 产品24 browse parent的计划键值对 >> ,阶段34,阶段124,阶段214,阶段304,阶段394,阶段484,阶段574
测试获取执行45 产品25 browse all的计划键值对 >> ,阶段35,阶段125,阶段215,阶段305,阶段395,阶段485,阶段575,子阶段5
测试获取执行45 产品25 browse parent的计划键值对 >> ,阶段35,阶段125,阶段215,阶段305,阶段395,阶段485,阶段575

*/
$executionIDList = array(41, 42, 43, 44, 45);
$productIDList   = array(21, 22, 23, 24, 25);
$browseTypeList  = array('all', 'parent');
$order           = 'id_desc';

$programplan = new programplanTest();

r($programplan->getStageTest($executionIDList[0], $productIDList[0], $browseTypeList[0]))         && p() && e(',阶段31,阶段121,阶段211,阶段301,阶段391,阶段481,阶段571,子阶段1'); // 测试获取执行41 产品21 browse all的计划键值对
r($programplan->getStageTest($executionIDList[0], $productIDList[0], $browseTypeList[1]))         && p() && e(',阶段31,阶段121,阶段211,阶段301,阶段391,阶段481,阶段571');         // 测试获取执行41 产品21 browse parent的计划键值对
r($programplan->getStageTest($executionIDList[0], $productIDList[0], $browseTypeList[0], $order)) && p() && e(',子阶段1,阶段571,阶段481,阶段391,阶段301,阶段211,阶段121,阶段31'); // 测试获取执行41 产品21 browse all id desc的计划键值对
r($programplan->getStageTest($executionIDList[1], $productIDList[1], $browseTypeList[0]))         && p() && e(',阶段32,阶段122,阶段212,阶段302,阶段392,阶段482,阶段572,子阶段2'); // 测试获取执行42 产品22 browse all的计划键值对
r($programplan->getStageTest($executionIDList[1], $productIDList[1], $browseTypeList[1]))         && p() && e(',阶段32,阶段122,阶段212,阶段302,阶段392,阶段482,阶段572');         // 测试获取执行42 产品22 browse parent的计划键值对
r($programplan->getStageTest($executionIDList[2], $productIDList[2], $browseTypeList[0]))         && p() && e(',阶段33,阶段123,阶段213,阶段303,阶段393,阶段483,阶段573,子阶段3'); // 测试获取执行43 产品23 browse all的计划键值对
r($programplan->getStageTest($executionIDList[2], $productIDList[2], $browseTypeList[1]))         && p() && e(',阶段33,阶段123,阶段213,阶段303,阶段393,阶段483,阶段573');         // 测试获取执行43 产品23 browse parent的计划键值对
r($programplan->getStageTest($executionIDList[3], $productIDList[3], $browseTypeList[0]))         && p() && e(',阶段34,阶段124,阶段214,阶段304,阶段394,阶段484,阶段574,子阶段4'); // 测试获取执行44 产品24 browse all的计划键值对
r($programplan->getStageTest($executionIDList[3], $productIDList[3], $browseTypeList[1]))         && p() && e(',阶段34,阶段124,阶段214,阶段304,阶段394,阶段484,阶段574');         // 测试获取执行44 产品24 browse parent的计划键值对
r($programplan->getStageTest($executionIDList[4], $productIDList[4], $browseTypeList[0]))         && p() && e(',阶段35,阶段125,阶段215,阶段305,阶段395,阶段485,阶段575,子阶段5'); // 测试获取执行45 产品25 browse all的计划键值对
r($programplan->getStageTest($executionIDList[4], $productIDList[4], $browseTypeList[1]))         && p() && e(',阶段35,阶段125,阶段215,阶段305,阶段395,阶段485,阶段575');         // 测试获取执行45 产品25 browse parent的计划键值对