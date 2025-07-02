#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->buildRadarChart();
timeout=0
cid=1

- 测试组件的key @Radar
- 测试series的name和type
 - 属性name @radar
 - 属性type @radar
- 测试雷达图的shape属性shape @polygon
- 测试雷达图存在数据 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zenData('product')->gen(5);
zenData('project')->loadYaml('program')->gen(5);
zenData('story')->loadYaml('story')->gen(20);
zenData('bug')->loadYaml('bug')->gen(15);

$screen = new screenTest();

global $tester;
$chart = $tester->dao->select('*')->from(TABLE_CHART)->where('id')->eq(1007)->fetch();

$component = json_decode($tester->config->screen->chartConfig['radar']);
$component->option = new stdClass();
$component->option->dataset = new stdClass();
$component->option->radar   = new stdClass();
$component->option->series  = array();

$component->option->series[0] = new stdClass();
$component->option->series[0]->data = array(new stdClass());

$component = $screen->buildRadarChart($component, $chart);

r($component->chartKey)                               && p('') && e('VRadar'); // 测试组件类型
r(isset($component->option->dataset->radarIndicator)) && p('') && e('1');      // 判断radarIndicator存在
r(isset($component->option->radar->indicator))        && p('') && e('1');      // 判断indicator存在

$radarIndicator = $component->option->dataset->radarIndicator;

r($radarIndicator[0]) && p('name,max') && e('产品管理,0');      // 判断radarIndicator[0]的name和max
r($radarIndicator[1]) && p('name,max') && e('项目管理,0');      // 判断radarIndicator[1]的name和max
