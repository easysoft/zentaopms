#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';


/**
title=测试 screenModel->completeComponent(), $screeModel->getChartFilters();
cid=1
pid=1

当chart为空时，使用默认配置。                                     >> transparent,1
当chart->stge为draft时，使用默认配置。                            >> 暂时没有数据,center
测试组件类型为chart并且chart->stage为chart时option的配置项。      >> 1,2
测试组件类型为chart并且chart->stage为chart时chartConfig的配置项。 >> 1,2
测试组件类型为chart并且chart->stage为chart时组件的类型。          >> chart
测试雷达组件是否能正常生成。                                      >> radar

*/

$chart = new stdclass();
$chart1 = new stdclass();
$chart1->name  = '测试图表';
$chart1->stage = 'draft';

$chart2 = new stdclass();
$chart2->name  = '测试图表';
$chart2->stage   = 'chart';
$chart2->deleted = 0;
$chart2->filters = '[{"from": "query", "type": "date"}]';
$chart2->sql     = 'select * from zt_user limit 1';
$chart2->fields  = "[field1,field2]";

$chart3 = new stdclass();
$chart3->id     = 1001;
$chart3->name  = '测试图表';
$chart3->stage   = 'chart';
$chart3->deleted = 0;
$chart3->filters = '[{"from": "query", "type": "date"}]';
$chart3->sql     = 'select * from zt_user limit 1';
$chart3->fields  = '{"id":{"type":"user","object":"","field":"id","name":"id"}}';
$chart3->builtin = false;
$chart3->settings  = '[{"yaxis":[{"field":"id", "valOrAgg":"count"}],"xaxis":[{"field":"id"}]}]';
$chart3->langs = "{}";

$component = new stdclass();
$component1 = new stdClass();
$component1->option = new stdclass();

$component2 = new stdclass();
$component2->option = new stdclass();
$component2->option->dataset = new stdclass();
$component2->option->dataset = [1,2];
$component2->type            = 'chart';
$component2->chartConfig = new stdclass();

$component3 = new stdclass();
$component3->option = new stdclass();
$component3->option->dataset = new stdclass();;
$component3->option->dataset->radarIndicator = [1,2];
$component3->option->dataset->seriesData     = [json_decode('{"name"":"test"}')];
$component3->type    = 'radar';
$component3->chartConfig = new stdclass();

$screen = new screenTest();

$typeList = array('pivot', 'chart');
$chartList = array($chart, $chart1, $chart2, $chart3);
$componentList = array($component, $component1, $component2, $component3);
$filterList = array(array());

$screen->completeComponentTest($chartList[1], $typeList[0], $filterList[0] ,$componentList[0]);
r($componentList[0]) && p('option:headerBGC,rowNum') && e('transparent,1');  //当chart为空时，使用默认配置。

$screen->completeComponentTest($chartList[1], $typeList[1], $filterList[0] ,$componentList[1]);
r($componentList[1]->option) && p('title:text,left') && e('暂时没有数据,center');  //当chart->stge为draft时，使用默认配置。

$screen->completeComponentTest($chartList[2], $typeList[0], $filterList[0] ,$componentList[2]);
r($componentList[2]->option)      && p('dataset:0,1') && e('1,2');  //测试组件类型为chart并且chart->stage为chart时option的配置项。
r($componentList[2]->chartConfig) && p('dataset:0,1') && e('1,2');  //测试组件类型为chart并且chart->stage为chart时chartConfig的配置项。
r($componentList[2])              && p('type') && e('chart');       //测试组件类型为chart并且chart->stage为chart时组件的类型。

$screen->completeComponentTest($chartList[3], $typeList[1], $filterList[0] ,$componentList[3]);
r($componentList[3]) && p('type') && e('radar');  //测试雷达组件是否能正常生成。
