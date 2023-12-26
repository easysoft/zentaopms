#!/usr/bin/env php
<?php
/**
title=fetchMetricsWithFilter
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$filters1 = array('scope' => array('system', 'execution'), 'object' => array('program', 'execution'), 'purpose' => array('scale', 'cost'));
$filters2 = array('scope' => array('execution'));
$filters3 = array('object' => array('execution'));
$filters4 = array('purpose' => array('hour'));

r($metric->fetchMetricsWithFilter($filters1)) && p('0:id,purpose,code;1:id,purpose,code') && e('1,scale,count_of_program;2,scale,count_of_doing_program');
r(count($metric->fetchMetricsWithFilter($filters1))) && p() && e(24);
r($metric->fetchMetricsWithFilter($filters2)) && p('0:id,purpose,code;1:id,purpose,code') && e('234,scale,count_of_story_in_execution;235,scale,count_of_finished_story_in_execution');
r(count($metric->fetchMetricsWithFilter($filters2))) && p() && e(15);
r($metric->fetchMetricsWithFilter($filters3)) && p('0:id,purpose,code;1:id,purpose,code') && e('45,scale,count_of_execution;46,scale,count_of_wait_execution');
r(count($metric->fetchMetricsWithFilter($filters3))) && p() && e(22);
r($metric->fetchMetricsWithFilter($filters4)) && p('0:id,purpose,code;1:id,purpose,code') && e('33,hour,estimate_of_annual_closed_project;34,hour,consume_of_annual_closed_project');
r(count($metric->fetchMetricsWithFilter($filters4))) && p() && e(19);
