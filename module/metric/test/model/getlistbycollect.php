#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getListByCollect();
timeout=0
cid=17104

- 执行metric模块的getListByCollect方法，参数是'all' 第0条的id属性 @8
- 执行metric模块的getListByCollect方法，参数是'released' 第0条的id属性 @8
- 执行metric模块的getListByCollect方法，参数是'wait'  @0
- 执行$result @2
- 执行metric模块的getListByCollect方法，参数是'all' 第0条的id属性 @9

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

// 设置测试用户为admin，并设置度量项10的收集者为admin
su('admin');
$metric->setCollector('10', 'admin');
$metric->setCollector('8', 'admin');
$metric->setCollector('9', 'user1');

// 测试步骤1：使用admin用户获取所有阶段的度量项列表
r($metric->getListByCollect('all')) && p('0:id') && e('8');

// 测试步骤2：使用stage参数为'released'获取已发布的度量项
r($metric->getListByCollect('released')) && p('0:id') && e('8');

// 测试步骤3：使用stage参数为'wait'获取等待阶段的度量项
r($metric->getListByCollect('wait')) && p() && e('0');

// 测试步骤4：使用stage参数为'all'获取所有阶段的度量项数量
$result = $metric->getListByCollect('all');
r(count($result)) && p() && e('2');

// 测试步骤5：切换到user1用户测试权限控制
su('user1');
r($metric->getListByCollect('all')) && p('0:id') && e('9');