#!/usr/bin/env php
<?php

/**

title=测试 executionZen::updateLinkedPlans();
timeout=0
cid=16448

- newPlans为空,confirm为no,不做任何操作 @0
- newPlans不为空,confirm为yes,关联计划到执行和项目属性result @success
- newPlans不为空,confirm为no,显示确认对话框属性result @success
- newPlans为单个计划,confirm为yes,正常关联属性result @success
- newPlans为多个计划,confirm为yes,批量关联属性result @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

su('admin');

$executionTest = new executionZenTest();

r($executionTest->updateLinkedPlansTest(1, '', 'no')) && p() && e('0'); // newPlans为空,confirm为no,不做任何操作
r($executionTest->updateLinkedPlansTest(1, '1,2,3', 'yes')) && p('result') && e('success'); // newPlans不为空,confirm为yes,关联计划到执行和项目
r($executionTest->updateLinkedPlansTest(2, '4,5', 'no')) && p('result') && e('success'); // newPlans不为空,confirm为no,显示确认对话框
r($executionTest->updateLinkedPlansTest(3, '6', 'yes')) && p('result') && e('success'); // newPlans为单个计划,confirm为yes,正常关联
r($executionTest->updateLinkedPlansTest(4, '7,8,9,10', 'yes')) && p('result') && e('success'); // newPlans为多个计划,confirm为yes,批量关联