#!/usr/bin/env php
<?php
/**

title=测试 chartModel::processChart();
cid=1
pid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/chart.class.php';

zdTable('module')->config('module')->gen(27);
zdTable('user')->gen(5);
su('admin');

$chart = new chartTest();
r($chart->processChartTest('decodeLangs'))    && p('langs[name]:zh-cn') && e('项目名称');   //测试解密langs字段
r($chart->processChartTest('decodeFilters'))  && p('filters[0]:field')  && e('closedDate'); //测试解密filters字段
r($chart->processChartTest('decodeSettings')) && p('settings[0]:type')  && e('cluBarY');    //测试解密settings字段

r($chart->processChartTest('sqlNull'))        && p('sql')               && e('~~'); //测试sql字段为null的情况
r($chart->processChartTest('trimSQL'))        && p('sql')               && e("SELECT id FROM zt_project WHERE type='program' AND parent=0 AND deleted='0'"); //测试sql字段为null的情况
