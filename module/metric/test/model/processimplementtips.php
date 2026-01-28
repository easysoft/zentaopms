#!/usr/bin/env php
<?php

/**

title=测试 metricModel::processImplementTips();
timeout=0
cid=17147

- 执行metricTest模块的processImplementTipsTest方法，参数是'count_of_bug'
 - 属性hasCodePlaceholder @0
 - 属性hasTmpRootPlaceholder @0
 - 属性tmpRootReplaced @1
- 执行metricTest模块的processImplementTipsTest方法，参数是''
 - 属性hasCodePlaceholder @0
 - 属性hasTmpRootPlaceholder @0
 - 属性tmpRootReplaced @1
- 执行metricTest模块的processImplementTipsTest方法，参数是'simple_metric'
 - 属性hasCodePlaceholder @0
 - 属性hasTmpRootPlaceholder @0
 - 属性tmpRootReplaced @1
- 执行metricTest模块的processImplementTipsTest方法，参数是'very_long_metric_code_name_with_many_underscores_and_characters'
 - 属性hasCodePlaceholder @0
 - 属性hasTmpRootPlaceholder @0
 - 属性tmpRootReplaced @1
- 执行metricTest模块的processImplementTipsTest方法，参数是'story_completion_rate'
 - 属性hasCodePlaceholder @0
 - 属性hasTmpRootPlaceholder @0
 - 属性tmpRootReplaced @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$metricTest = new metricModelTest();

r($metricTest->processImplementTipsTest('count_of_bug')) && p('hasCodePlaceholder,hasTmpRootPlaceholder,tmpRootReplaced') && e('0,0,1');
r($metricTest->processImplementTipsTest('')) && p('hasCodePlaceholder,hasTmpRootPlaceholder,tmpRootReplaced') && e('0,0,1');
r($metricTest->processImplementTipsTest('simple_metric')) && p('hasCodePlaceholder,hasTmpRootPlaceholder,tmpRootReplaced') && e('0,0,1');
r($metricTest->processImplementTipsTest('very_long_metric_code_name_with_many_underscores_and_characters')) && p('hasCodePlaceholder,hasTmpRootPlaceholder,tmpRootReplaced') && e('0,0,1');
r($metricTest->processImplementTipsTest('story_completion_rate')) && p('hasCodePlaceholder,hasTmpRootPlaceholder,tmpRootReplaced') && e('0,0,1');