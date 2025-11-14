#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getFormFieldsForReview();
timeout=0
cid=18687

- 测试获取软件需求评审表单字段的基本结构
 - 第result条的control属性 @picker
 - 第result条的required属性 @1
- 测试获取用户需求评审表单字段的assignedTo字段第assignedTo条的control属性 @picker
- 测试reviewing状态且版本为1的需求评审字段的pri默认值第pri条的default属性 @3
- 测试reviewing状态且版本大于1的需求评审字段的estimate默认值第estimate条的default属性 @9
- 测试active状态需求的评审表单字段的status默认值第status条的default属性 @closed
- 测试评审表单字段的comment字段control属性第comment条的control属性 @editor

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

zenData('story')->loadYaml('getformfieldsforreview/story')->gen(20);
zenData('user')->loadYaml('getformfieldsforreview/user')->gen(10);
zenData('product')->gen(5);

su('admin');

$storyIDs = array(1, 6, 11, 13, 7, 3);

$storyTester = new storyZenTest();
r($storyTester->getFormFieldsForReviewTest($storyIDs[0])) && p('result:control,required') && e('picker,1'); // 测试获取软件需求评审表单字段的基本结构
r($storyTester->getFormFieldsForReviewTest($storyIDs[1])) && p('assignedTo:control') && e('picker'); // 测试获取用户需求评审表单字段的assignedTo字段
r($storyTester->getFormFieldsForReviewTest($storyIDs[2])) && p('pri:default') && e('3'); // 测试reviewing状态且版本为1的需求评审字段的pri默认值
r($storyTester->getFormFieldsForReviewTest($storyIDs[3])) && p('estimate:default') && e('9'); // 测试reviewing状态且版本大于1的需求评审字段的estimate默认值
r($storyTester->getFormFieldsForReviewTest($storyIDs[4])) && p('status:default') && e('closed'); // 测试active状态需求的评审表单字段的status默认值
r($storyTester->getFormFieldsForReviewTest($storyIDs[5])) && p('comment:control') && e('editor'); // 测试评审表单字段的comment字段control属性