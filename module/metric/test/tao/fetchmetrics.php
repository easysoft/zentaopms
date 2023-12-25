#!/usr/bin/env php
<?php
/**
title=fetchMetrics
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

r($metric->fetchMetrics('project', 'all')) && p('0:id,purpose,code') && e('231,hour,ac_of_all_in_waterfall');                                     // 查询项目范围的第一个度量项
r($metric->fetchMetrics('project', 'wait')) && p() && e('0');                                                                                     // 查询项目范围内为草稿的第一个度量项
r($metric->fetchMetrics('system', 'all', 'program', 'scale')) && p('0:id,purpose,code') && e('10,scale,count_of_annual_closed_top_program');      // 查询系统范围内对象为项目集，目的为规模估算的第一个度量项
r($metric->fetchMetrics('system', 'all', 'program', 'scale', '', 'id_asc')) && p('0:id,purpose,code') && e('1,scale,count_of_program');           // 查询系统范围内对象为项目集，目的为规模估算的id正数第一个度量项
r($metric->fetchMetrics('product', 'all', 'story', 'scale', '', 'id_asc')) && p('0:id,purpose,code') && e('141,scale,count_of_story_in_product'); // 查询产品范围内对象为需求，目的为规模估算的id正数第一个度量项
r($metric->fetchMetrics('project', 'all', 'task', 'rate')) && p('0:id,purpose,code') && e('224,rate,cv_in_waterfall');                            // 查询项目范围内对象为任务，目的为效率提升的第一个度量项
