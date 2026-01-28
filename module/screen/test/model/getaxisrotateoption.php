#!/usr/bin/env php
<?php
declare(strict_types = 1);

/**

title=测试 screenModel->getAxisRotateOption();
timeout=0
cid=18235

- 判断折线图x轴旋转角度 @30
- 判断折线图y轴旋转角度 @30
- 判断折线图仅设置x轴旋转时x轴旋转角度 @30
- 判断折线图仅设置x轴旋转时y轴旋转角度 @0
- 判断雷达图是否设置x轴旋转角度 @0
- 判断雷达图是否设置y轴旋转角度 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$screen = new screenModelTest();

global $tester;

$chart1 = new stdClass();
$chart1->type     = 'line';
$chart1->settings = json_encode(array(array('rotateX' => 'use', 'rotateY' => 'use')));

$component = new stdClass();
$component->chartConfig = new stdClass();

$component = $screen->getAxisRotateOption($chart1, $component);

r($component->chartConfig->xAxis->axisLabel->rotate) && p('') && e('30'); // 判断折线图x轴旋转角度
r($component->chartConfig->yAxis->axisLabel->rotate) && p('') && e('30'); // 判断折线图y轴旋转角度

$chart2 = new stdClass();
$chart2->type     = 'line';
$chart2->settings = json_encode(array(array('rotateX' => 'use')));

$component = new stdClass();
$component->chartConfig = new stdClass();

$component = $screen->getAxisRotateOption($chart2, $component);

r($component->chartConfig->xAxis->axisLabel->rotate) && p('') && e('30'); // 判断折线图仅设置x轴旋转时x轴旋转角度
r($component->chartConfig->yAxis->axisLabel->rotate) && p('') && e('0'); // 判断折线图仅设置x轴旋转时y轴旋转角度

$chart3 = new stdClass();
$chart3->type     = 'radar';
$chart3->settings = json_encode(array(array('rotateX' => 'use')));

$component = new stdClass();
$component->chartConfig = new stdClass();

$component = $screen->getAxisRotateOption($chart3, $component);

r(isset($component->chartConfig->xAxis->axisLabel->rotate)) && p('') && e('0'); // 判断雷达图是否设置x轴旋转角度
r(isset($component->chartConfig->yAxis->axisLabel->rotate)) && p('') && e('0'); // 判断雷达图是否设置y轴旋转角度