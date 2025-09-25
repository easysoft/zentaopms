#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

/**

title=测试 chartModel::genCluBar();
timeout=0
cid=0

- 测试正常簇状条形图生成的series第0个元素的type属性第series[0]条的type属性 @bar
- 测试堆积条形图生成的series第0个元素的stack属性第series[0]条的stack属性 @total
- 测试垂直簇状条形图生成的xAxis的type属性第xAxis条的type属性 @value
- 测试带过滤器的条形图生成的tooltip的trigger属性第tooltip条的trigger属性 @axis
- 测试带多语言标签的条形图生成的grid的containLabel属性第grid条的containLabel属性 @1

*/

$chartTest = new chartTest();

r($chartTest->genCluBarTest('normal')) && p('series[0]:type') && e('bar'); // 测试正常簇状条形图生成的series第0个元素的type属性
r($chartTest->genCluBarTest('stackedBar')) && p('series[0]:stack') && e('total'); // 测试堆积条形图生成的series第0个元素的stack属性
r($chartTest->genCluBarTest('cluBarY')) && p('xAxis:type') && e('value'); // 测试垂直簇状条形图生成的xAxis的type属性
r($chartTest->genCluBarTest('withFilters')) && p('tooltip:trigger') && e('axis'); // 测试带过滤器的条形图生成的tooltip的trigger属性
r($chartTest->genCluBarTest('withLangs')) && p('grid:containLabel') && e('1'); // 测试带多语言标签的条形图生成的grid的containLabel属性