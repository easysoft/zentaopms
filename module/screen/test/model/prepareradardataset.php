#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 screenModel->prepareRadarDataset();
timeout=0
cid=18273

- 执行screen模块的prepareRadarDatasetTest方法，参数是'normal' 第radarIndicator条的0属性 @indicator1
- 执行screen模块的prepareRadarDatasetTest方法，参数是'normal' 第seriesData条的0属性 @series1
- 执行screen模块的prepareRadarDatasetTest方法，参数是'empty_indicator' 属性radarCount @0
- 执行screen模块的prepareRadarDatasetTest方法，参数是'empty_series' 属性seriesCount @0
- 执行screen模块的prepareRadarDatasetTest方法，参数是'normal' 属性result @object

*/

$screen = new screenModelTest();

r($screen->prepareRadarDatasetTest('normal')) && p('radarIndicator:0') && e('indicator1');
r($screen->prepareRadarDatasetTest('normal')) && p('seriesData:0') && e('series1');
r($screen->prepareRadarDatasetTest('empty_indicator')) && p('radarCount') && e('0');
r($screen->prepareRadarDatasetTest('empty_series')) && p('seriesCount') && e('0');
r($screen->prepareRadarDatasetTest('normal')) && p('result') && e('object');