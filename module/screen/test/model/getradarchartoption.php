#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->getRadarChartOption();
timeout=0
cid=18255

- 测试组件类型 @VRadar
- 判断radarIndicator存在 @1
- 判断indicator存在 @1
- 判断radarIndicator[0]的name和max
 - 属性name @产品管理
 - 属性max @0
- 判断radarIndicator[1]的name和max
 - 属性name @项目管理
 - 属性max @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->gen(5);
zenData('project')->loadYaml('program')->gen(5);
zenData('story')->loadYaml('story')->gen(20);
zenData('bug')->loadYaml('bug')->gen(15);
zenData('action')->loadYaml('action')->gen(100);

$screen = new screenModelTest();

global $tester;
$chart = new stdClass();
$chart->id       = 1007;
$chart->type     = 'radar';
$chart->driver   = 'mysql';
$chart->name     = '测试雷达图';
$chart->settings = '[{"type":"radar","xaxis":[{"field":"action","name":"\u52a8\u4f5c","group":""}],"yaxis":[{"field":"action","name":"\u52a8\u4f5c","valOrAgg":"count"}]}]';
$chart->fields   = '{"action":{"name":"\u52a8\u4f5c","object":"action","field":"action","type":"string"}}';
$chart->langs    = '';
$chart->sql      = 'select action from zt_action';


$component = json_decode($tester->config->screen->chartConfig['radar']);
$component->option = new stdClass();
$component->option->dataset = new stdClass();
$component->option->radar   = new stdClass();
$component->option->series  = array();


$component = $screen->getRadarChartOption($component, $chart);

r($component->chartKey)                               && p('') && e('VRadar'); // 测试组件类型
r(isset($component->option->dataset->radarIndicator)) && p('') && e('1');      // 判断radarIndicator存在
r(isset($component->option->dataset->seriesData))     && p('') && e('1');      // 判断seriesData存在

$radarIndicator = $component->option->dataset->radarIndicator;

r($radarIndicator[0]) && p('name,max') && e('3,3');      // 判断radarIndicator[0]的name和max
r($radarIndicator[1]) && p('name,max') && e('2,3');      // 判断radarIndicator[1]的name和max
