#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

zdTable('bug')->config('bug')->gen(20);
/**
title=测试 pivotModel->getBugs();
cid=1
pid=1

测试获取一个月内的bug数据统计                       >> admin,10,1;user1,33.33%,10,1
测试获取六个月前到四个月前的bug统计数据             >> 0,0,0
测试获取id为1的产品下的bug统计数据                  >> admin,3,0
测试获取执行id为101的执行下的bug统计数据            >> admin,3,0
测试获取id为1的产品以及id为101的执行下的bug统计数据 >> admin,3,0

*/

$pivot = new pivotTest();

$date1_start = date('Y-m-d', strtotime('last month', strtotime(date('Y-m',time()) . '-01 00:00:01')));
$date1_end   = date('Y-m-d', strtotime('now'));

$date2_start = date('Y-m-d', strtotime('-2 month'));
$date2_end   = date('Y-m-d', strtotime('-1 month -1 day'));

$productList   = array(0, 1);
$executionList = array(0, 101);

r($pivot->getBugs($date1_start, $date1_end, $productList[0], $executionList[0])) && p('0:openedBy,total,tostory;1:openedBy,total,tostory') && e('admin,10,1;user1,10,1');  //测试获取一个月内的bug数据统计
r($pivot->getBugs($date2_start, $date2_end, $productList[0], $executionList[0])) && p('0:openedBy,total,tostory') && e('0,0,0,0');                                                              //测试获取六个月前到四个月前的bug统计数据
r($pivot->getBugs($date1_start, $date1_end, $productList[1], $executionList[0])) && p('0:openedBy,total,tostory') && e('admin,3,0');                                                         //测试获取id为1的产品下的bug统计数据
r($pivot->getBugs($date1_start, $date1_end, $productList[0], $executionList[1])) && p('0:openedBy,total,tostory') && e('admin,3,0');                                                         //测试获取执行id为101的执行下的bug统计数据
r($pivot->getBugs($date1_start, $date1_end, $productList[1], $executionList[1])) && p('0:openedBy,total,tostory') && e('admin,3,0');                                                         //测试获取id为1的产品以及id为101的执行下的bug统计数据
