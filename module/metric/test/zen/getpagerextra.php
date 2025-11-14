#!/usr/bin/env php
<?php

/**

title=测试 metricZen::getPagerExtra();
timeout=0
cid=17192

- 执行metricZenTest模块的getPagerExtraZenTest方法，参数是500  @0
- 执行metricZenTest模块的getPagerExtraZenTest方法，参数是400  @0
- 执行metricZenTest模块的getPagerExtraZenTest方法，参数是301  @0
- 执行metricZenTest模块的getPagerExtraZenTest方法，参数是300  @shortPageSize
- 执行metricZenTest模块的getPagerExtraZenTest方法，参数是200  @shortPageSize
- 执行metricZenTest模块的getPagerExtraZenTest方法  @shortPageSize
- 执行metricZenTest模块的getPagerExtraZenTest方法，参数是-100  @shortPageSize

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

su('admin');

$metricZenTest = new metricZenTest();

r($metricZenTest->getPagerExtraZenTest(500)) && p() && e('0');
r($metricZenTest->getPagerExtraZenTest(400)) && p() && e('0');
r($metricZenTest->getPagerExtraZenTest(301)) && p() && e('0');
r($metricZenTest->getPagerExtraZenTest(300)) && p() && e('shortPageSize');
r($metricZenTest->getPagerExtraZenTest(200)) && p() && e('shortPageSize');
r($metricZenTest->getPagerExtraZenTest(0)) && p() && e('shortPageSize');
r($metricZenTest->getPagerExtraZenTest(-100)) && p() && e('shortPageSize');