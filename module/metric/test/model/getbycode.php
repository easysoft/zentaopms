#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getByCode();
timeout=0
cid=17078

- 测试步骤1：获取有效code的完整度量信息
 - 属性name @按系统统计的年度关闭一级项目集数
 - 属性code @count_of_annual_closed_top_program
 - 属性purpose @scale
- 测试步骤2：使用指定字段列表获取度量信息
 - 属性id @127
 - 属性name @按系统统计的年度投入总人天
- 测试步骤3：使用数组形式的字段列表获取度量信息
 - 属性id @127
 - 属性code @day_of_annual_effort
 - 属性purpose @hour
- 测试步骤4：获取不存在的度量code @0
- 测试步骤5：验证静态缓存机制
 - 属性name @按系统统计的年度关闭一级项目集数
 - 属性code @count_of_annual_closed_top_program
- 测试步骤6：测试空字符串code @0
- 测试步骤7：测试特殊字符code @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

r($metric->getByCode('count_of_annual_closed_top_program')) && p('name,code,purpose') && e('按系统统计的年度关闭一级项目集数,count_of_annual_closed_top_program,scale'); // 测试步骤1：获取有效code的完整度量信息
r($metric->getByCode('day_of_annual_effort', 'id,name'))    && p('id,name') && e('127,按系统统计的年度投入总人天'); // 测试步骤2：使用指定字段列表获取度量信息
r($metric->getByCode('day_of_annual_effort', array('id', 'code', 'purpose'))) && p('id,code,purpose') && e('127,day_of_annual_effort,hour'); // 测试步骤3：使用数组形式的字段列表获取度量信息
r($metric->getByCode('nonexistent_metric_code'))           && p() && e('0'); // 测试步骤4：获取不存在的度量code
r($metric->getByCode('count_of_annual_closed_top_program')) && p('name,code') && e('按系统统计的年度关闭一级项目集数,count_of_annual_closed_top_program'); // 测试步骤5：验证静态缓存机制
r($metric->getByCode(''))                                   && p() && e('0'); // 测试步骤6：测试空字符串code
r($metric->getByCode('metric@#$%^&*()'))                    && p() && e('0'); // 测试步骤7：测试特殊字符code