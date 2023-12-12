#!/usr/bin/env php
<?php
/**
title=测试 pivotModel->setExecutionName();
cid=1
pid=1

测试当execution的multiple为0时，生成的executionName为空     >> 空
测试当execution的multiple为1时，生成的executionName为executionName值本身、 >> name1
测试当execution的multiple为1时，生成的executionName为带了标签的值。 >> 1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

$pivot = new pivotTest();

$execution1 = new stdclass();
$execution1->multiple = 0;

$execution2 = new stdclass();
$execution2->executionID = 1;
$execution2->multiple = 1;
$execution2->executionName = 'name1';

$execution3 = new stdclass();
$execution3->executionID = 2;
$execution3->multiple = 1;
$execution3->executionName = 'name2';

$execution3_ = clone($execution3);

$executionList = array($execution1, $execution2, $execution3);
$canViewList   = array(false, false, true);

$pivot->setExecutionName($executionList[0], $canViewList[0]);
$pivot->setExecutionName($executionList[1], $canViewList[1]);
$pivot->setExecutionName($executionList[2], $canViewList[2]);

r($execution1->executionName) && p('') && e('空');  //测试当execution的multiple为0时，生成的executionName为空
r($execution2->executionName) && p('') && e('name1');  //测试当execution的multiple为1时，生成的executionName为executionName值本身、
r($execution3->executionName && trim(strip_tags($execution3->executionName)) == trim($execution3_->executionName)) && p('') && e('1');  //测试当execution的multiple为1时，生成的executionName为带了标签的值。
