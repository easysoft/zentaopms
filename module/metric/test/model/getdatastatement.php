#!/usr/bin/env php
<?php
/**
title=getDataStatement
timeout=0
cid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';
su('admin');

$metric = new metricTest();

$metricList = array();
$metricList[0] = 'count_of_bug';
$metricList[1] = 'count_of_annual_created_product';
$metricList[2] = 'count_of_weekly_created_release';
$metricList[3] = 'count_of_case_in_product';
$metricList[4] = 'count_of_annual_fixed_bug_in_product';
$metricList[5] = 'count_of_daily_closed_bug_in_product';

$expectSQL = array();
$expectSQL[0] = "select t1.id from `zt_bug` as t1  left join `zt_product` as t2  on t1.product=t2.id  where t1.deleted  = '0' and  t2.deleted  = '0' and  t2.shadow  = '0'";
$expectSQL[1] = "select t1.createddate from `zt_product` as t1  where t1.deleted  = '0' and  t1.shadow  = '0' and  not find_in_set('or', t1.vision)  and  not find_in_set('lite', t1.vision)";
$expectSQL[2] = "select t1.createddate from `zt_release` as t1  left join `zt_project` as t2  on concat(',', t2.id, ',') like concat('%', t1.project, '%')  left join `zt_product` as t3  on t1.product=t3.id  where t1.deleted  = '0' and  t3.deleted  = '0' and  not find_in_set('or', t2.vision)  and  not find_in_set('lite', t2.vision)";
$expectSQL[3] = "select t1.product from `zt_case` as t1  left join `zt_product` as t2  on t1.product=t2.id  where t1.deleted  = '0' and  t2.deleted  = '0' and  t2.shadow  = '0' and  not find_in_set('or', t2.vision)  and  not find_in_set('lite', t2.vision)";
$expectSQL[4] = "select t1.product,t1.resolution,t1.closeddate from `zt_bug` as t1  left join `zt_product` as t2  on t1.product=t2.id  where t1.deleted  = '0' and  t2.deleted  = '0' and  t2.shadow  = '0'";
$expectSQL[5] = "select t1.product,t1.status,t1.closeddate from `zt_bug` as t1  left join `zt_product` as t2  on t1.product=t2.id  where t1.deleted  = '0' and  t2.deleted  = '0' and  t2.shadow  = '0'";

r($metric->getDataStatement($metricList[0], 'system',  'scale')) && p('') && e("select t1.id from `zt_bug` as t1  left join `zt_product` as t2  on t1.product=t2.id  where t1.deleted  = '0' and  t2.deleted  = '0' and  t2.shadow  = '0'");                                                                                                                                                                   // 测试count_of_bug
r($metric->getDataStatement($metricList[1], 'system',  'scale')) && p('') && e("select t1.createddate from `zt_product` as t1  where t1.deleted  = '0' and  t1.shadow  = '0' and  not find_in_set('or', t1.vision)  and  not find_in_set('lite', t1.vision)");                                                                                                                                                 // 测试count_of_annual_created_product
r($metric->getDataStatement($metricList[2], 'system',  'scale')) && p('') && e("select t1.createddate from `zt_release` as t1  left join `zt_project` as t2  on concat(',', t2.id, ',') like concat('%', t1.project, '%')  left join `zt_product` as t3  on t1.product=t3.id  where t1.deleted  = '0' and  t3.deleted  = '0' and  not find_in_set('or', t2.vision)  and  not find_in_set('lite', t2.vision)"); // 测试count_of_weekly_created_release
r($metric->getDataStatement($metricList[3], 'product', 'scale')) && p('') && e("select t1.product from `zt_case` as t1  left join `zt_product` as t2  on t1.product=t2.id  where t1.deleted  = '0' and  t2.deleted  = '0' and  t2.shadow  = '0' and  not find_in_set('or', t2.vision)  and  not find_in_set('lite', t2.vision)");                                                                              // 测试count_of_case_in_product
r($metric->getDataStatement($metricList[4], 'product', 'scale')) && p('') && e("select t1.product,t1.resolution,t1.closeddate from `zt_bug` as t1  left join `zt_product` as t2  on t1.product=t2.id  where t1.deleted  = '0' and  t2.deleted  = '0' and  t2.shadow  = '0'");                                                                                                                                  // 测试count_of_annual_fixed_bug_in_product
r($metric->getDataStatement($metricList[5], 'product', 'scale')) && p('') && e("select t1.product,t1.status,t1.closeddate from `zt_bug` as t1  left join `zt_product` as t2  on t1.product=t2.id  where t1.deleted  = '0' and  t2.deleted  = '0' and  t2.shadow  = '0'");                                                                                                                                      // 测试count_of_daily_closed_bug_in_product
