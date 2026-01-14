#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 screenModel->setValueByPath();
timeout=0
cid=18283

- 测试title.show=true @1
- 测试series.0.color.0.colorStops.0.offset=1 @1
- 测试series.0.color.0.x=#fff @#fff
- 测试series.0.color.1.y=#000 @#000
- 测试series.0.0.name=data @data

*/

$screen = new screenModelTest();
$paths = array
(
    'title.show',
    'series.0.color.0.colorStops.0.offset',
    'series.0.color.0.x',
    'series.0.color.1.y',
    'series.0.0.name',
);

$options = new stdclass();
$screen->setValueByPathTest($options, $paths[0], true);
$screen->setValueByPathTest($options, $paths[1], 1);
$screen->setValueByPathTest($options, $paths[2], '#fff');
$screen->setValueByPathTest($options, $paths[3], '#000');

r($options->title->show)                                && p('') && e("1");    //测试title.show=true
r($options->series[0]->color[0]->colorStops[0]->offset) && p('') && e("1");    //测试series.0.color.0.colorStops.0.offset=1
r($options->series[0]->color[0]->x)                     && p('') && e("#fff"); //测试series.0.color.0.x=#fff
r($options->series[0]->color[1]->y)                     && p('') && e("#000"); //测试series.0.color.1.y=#000

$options = new stdclass();
$screen->setValueByPathTest($options, $paths[4], 'data');

r($options->series[0][0]->name) && p('') && e("data");    //测试series.0.0.name=data