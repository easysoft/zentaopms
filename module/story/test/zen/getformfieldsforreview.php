#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getFormFieldsForReview();
timeout=0
cid=18684

- 步骤1：测试需求1的评审表单字段第reviewedDate条的type属性 @date
- 步骤2：测试需求2的评审表单字段
 - 第result条的type属性 @string
 - 第result条的control属性 @picker
- 步骤3：测试需求3的评审表单字段
 - 第assignedTo条的type属性 @string
 - 第assignedTo条的control属性 @picker
 - 第assignedTo条的name属性 @assignedTo
- 步骤4：测试需求4的评审表单字段
 - 第status条的type属性 @string
 - 第status条的control属性 @hidden
 - 第status条的name属性 @status
- 步骤5：测试需求5的评审表单字段
 - 第pri条的type属性 @int
 - 第pri条的control属性 @priPicker
 - 第pri条的name属性 @pri

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

su('admin');

$storyTest = new storyZenTest();

r($storyTest->getFormFieldsForReviewTest(1)) && p('reviewedDate:type')            && e('date');                     // 步骤1：测试需求1的评审表单字段
r($storyTest->getFormFieldsForReviewTest(2)) && p('result:type,control')          && e('string,picker');            // 步骤2：测试需求2的评审表单字段
r($storyTest->getFormFieldsForReviewTest(3)) && p('assignedTo:type,control,name') && e('string,picker,assignedTo'); // 步骤3：测试需求3的评审表单字段
r($storyTest->getFormFieldsForReviewTest(4)) && p('status:type,control,name')     && e('string,hidden,status');     // 步骤4：测试需求4的评审表单字段
r($storyTest->getFormFieldsForReviewTest(5)) && p('pri:type,control,name')        && e('int,priPicker,pri');        // 步骤5：测试需求5的评审表单字段
