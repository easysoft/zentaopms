#!/usr/bin/env php
<?php
/**
title=getByID
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->getByID(1)) && p('name') && e('按系统统计的所有层级的项目集总数');    // 获取id为1的度量名称
r($metric->getByID(10)) && p('code') && e('count_of_annual_closed_top_program'); // 获取id为10的度量代号
r($metric->getByID(100, 'id')) && p('name') && e('~~');                          // 获取id为100并指定fieldList后检测名称不应该存在
r($metric->getByID(0)) && p('name') && e('0');                                  // 获取一个不存在的度量
