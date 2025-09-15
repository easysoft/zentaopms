#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genRadar();
timeout=0
cid=0

- 执行chartTest模块的genRadarTest方法，参数是'normal' 属性series @radar
属性type @radar
- 执行chartTest模块的genRadarTest方法，参数是'multi' 属性series @数量(计数)
属性data @数量(计数)
 @数量(计数)
属性name @数量(计数)
- 执行chartTest模块的genRadarTest方法，参数是'empty' 属性radar @~~
属性indicator @~~
- 执行chartTest模块的genRadarTest方法，参数是'filtered' 属性series @radar
属性type @radar
- 执行chartTest模块的genRadarTest方法，参数是'multilang' 属性series @计数值(计数)
属性data @计数值(计数)
 @计数值(计数)
属性name @计数值(计数)

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

su('admin');
$chartTest = new chartTest();

// 5个测试步骤
r($chartTest->genRadarTest('normal')) && p('series,type') && e('radar');
r($chartTest->genRadarTest('multi')) && p('series,data,0,name') && e('数量(计数)');
r($chartTest->genRadarTest('empty')) && p('radar,indicator') && e('~~');
r($chartTest->genRadarTest('filtered')) && p('series,type') && e('radar');
r($chartTest->genRadarTest('multilang')) && p('series,data,0,name') && e('计数值(计数)');