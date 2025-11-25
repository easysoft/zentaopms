#!/usr/bin/env php
<?php

/**

title=fetchMetrics
timeout=0
cid=17168

- 查询项目范围的第一个度量项
 - 第0条的id属性 @306
 - 第0条的purpose属性 @scale
 - 第0条的code属性 @count_of_finished_epic_in_project
- 查询项目范围内为草稿的第一个度量项 @0
- 查询系统范围内对象为项目集，目的为规模估算的第一个度量项
 - 第0条的id属性 @10
 - 第0条的purpose属性 @scale
 - 第0条的code属性 @count_of_annual_closed_top_program
- 查询系统范围内对象为项目集，目的为规模估算的id正数第一个度量项
 - 第0条的id属性 @1
 - 第0条的purpose属性 @scale
 - 第0条的code属性 @count_of_program
- 查询产品范围内对象为需求，目的为规模估算的id正数第一个度量项
 - 第0条的id属性 @150
 - 第0条的purpose属性 @scale
 - 第0条的code属性 @count_of_story_in_product
- 查询项目范围内对象为任务，目的为效率提升的第一个度量项
 - 第0条的id属性 @283
 - 第0条的purpose属性 @rate
 - 第0条的code属性 @cv_weekly_in_waterfall

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

r($metric->fetchMetrics('project', 'all')) && p('0:id,purpose,code') && e('306,scale,count_of_finished_epic_in_project');                         // 查询项目范围的第一个度量项
r($metric->fetchMetrics('project', 'wait')) && p() && e('0');                                                                                     // 查询项目范围内为草稿的第一个度量项
r($metric->fetchMetrics('system', 'all', 'program', 'scale')) && p('0:id,purpose,code') && e('10,scale,count_of_annual_closed_top_program');      // 查询系统范围内对象为项目集，目的为规模估算的第一个度量项
r($metric->fetchMetrics('system', 'all', 'program', 'scale', '', 'id_asc')) && p('0:id,purpose,code') && e('1,scale,count_of_program');           // 查询系统范围内对象为项目集，目的为规模估算的id正数第一个度量项
r($metric->fetchMetrics('product', 'all', 'story', 'scale', '', 'id_asc')) && p('0:id,purpose,code') && e('150,scale,count_of_story_in_product'); // 查询产品范围内对象为需求，目的为规模估算的id正数第一个度量项
r($metric->fetchMetrics('project', 'all', 'task', 'rate')) && p('0:id,purpose,code') && e('283,rate,cv_weekly_in_waterfall');                     // 查询项目范围内对象为任务，目的为效率提升的第一个度量项