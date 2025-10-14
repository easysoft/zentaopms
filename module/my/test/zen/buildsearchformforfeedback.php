#!/usr/bin/env php
<?php

/**

title=测试 myZen::buildSearchFormForFeedback();
timeout=0
cid=0

- 执行myTest模块的buildSearchFormForFeedbackTest方法，参数是1, 'id_desc', 'feedback' 
 - 属性module @feedbackFeedback
 - 属性queryID @1
 - 属性hasActionURL @1
- 执行myTest模块的buildSearchFormForFeedbackTest方法，参数是0, 'id_asc', 'feedback' 
 - 属性queryID @0
 - 属性hasActionURL @1
 - 属性hasProductValues @1
- 执行myTest模块的buildSearchFormForFeedbackTest方法，参数是5, '', 'feedback' 
 - 属性queryID @5
 - 属性hasModuleValues @1
 - 属性hasProcessedByValues @1
- 执行myTest模块的buildSearchFormForFeedbackTest方法，参数是1, 'id_desc', 'work' 
 - 属性module @workFeedback
 - 属性hasActionURL @1
- 执行myTest模块的buildSearchFormForFeedbackTest方法，参数是10, 'status_asc', 'feedback' 
 - 属性module @feedbackFeedback
 - 属性hasProductValues @1
 - 属性hasModuleValues @1
 - 属性hasProcessedByValues @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('product')->gen(3);
zenData('module')->gen(5);
zenData('user')->gen(10);

su('admin');

$myTest = new myTest();

r($myTest->buildSearchFormForFeedbackTest(1, 'id_desc', 'feedback')) && p('module,queryID,hasActionURL') && e('feedbackFeedback,1,1');
r($myTest->buildSearchFormForFeedbackTest(0, 'id_asc', 'feedback')) && p('queryID,hasActionURL,hasProductValues') && e('0,1,1');
r($myTest->buildSearchFormForFeedbackTest(5, '', 'feedback')) && p('queryID,hasModuleValues,hasProcessedByValues') && e('5,1,1');
r($myTest->buildSearchFormForFeedbackTest(1, 'id_desc', 'work')) && p('module,hasActionURL') && e('workFeedback,1');
r($myTest->buildSearchFormForFeedbackTest(10, 'status_asc', 'feedback')) && p('module,hasProductValues,hasModuleValues,hasProcessedByValues') && e('feedbackFeedback,1,1,1');