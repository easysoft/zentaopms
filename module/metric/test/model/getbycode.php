#!/usr/bin/env php
<?php

/**

title=getByCode
timeout=0
cid=1

- 获取code为count_of_annual_closed_top_program的度量名称属性name @按系统统计的年度关闭一级项目集数
- 获取code为day_of_annual_effort的度量名称属性name @按系统统计的年度投入总人天
- 获取code为day_of_annual_effort的度量名称，传入了查询的字段，因此查询不到属性name @~~
- 获取一个不存在的code属性name @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->getByCode('count_of_annual_closed_top_program')) && p('name') && e('按系统统计的年度关闭一级项目集数'); // 获取code为count_of_annual_closed_top_program的度量名称
r($metric->getByCode('day_of_annual_effort'))               && p('name') && e('按系统统计的年度投入总人天');       // 获取code为day_of_annual_effort的度量名称
r($metric->getByCode('day_of_annual_effort', 'id'))         && p('name') && e('~~');                               // 获取code为day_of_annual_effort的度量名称，传入了查询的字段，因此查询不到
r($metric->getByCode('day_of_annual_login'))                && p('name') && e('0');                                // 获取一个不存在的code