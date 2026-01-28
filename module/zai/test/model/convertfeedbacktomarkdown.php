#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertFeedbackToMarkdown();
timeout=0
cid=19766

- 测试转换完整的反馈对象 @1
- 测试转换第二个反馈对象 @2
- 测试验证返回了attrs属性 @1
- 测试验证第二个对象返回了attrs属性 @1
- 测试验证生成了content @1
- 测试验证第二个对象生成了content @1
- 测试验证生成了title @1
- 测试验证第二个对象生成了title @1
- 测试返回数组结构 @1
- 测试第二个对象返回数组结构 @1
- 测试验证产品属性 @1
- 测试验证类型属性 @bug
- 测试验证反馈类型转换正确 @suggest
- 测试验证反馈状态处理 @active
- 测试验证反馈优先级设置 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('feedback')->gen(0);

su('admin');

global $tester;
$zai = new zaiModelTest();

// 创建完整的反馈对象
$feedback1 = new stdClass();
$feedback1->id = 1;
$feedback1->title = '测试反馈1 - 用户界面问题';
$feedback1->feedbackBy = 'user001';
$feedback1->type = 'bug';
$feedback1->pri = 2;
$feedback1->status = 'active';
$feedback1->solution = '';
$feedback1->product = 1;
$feedback1->module = 1;
$feedback1->openedBy = 'admin';
$feedback1->openedDate = '2023-01-01 10:00:00';
$feedback1->assignedTo = 'developer1';
$feedback1->assignedDate = '2023-01-01 11:00:00';
$feedback1->reviewedBy = '';
$feedback1->reviewedDate = '';
$feedback1->closedBy = '';
$feedback1->closedDate = '';
$feedback1->closedReason = '';
$feedback1->processedBy = '';
$feedback1->processedDate = '';
$feedback1->source = 'manual';
$feedback1->result = '';
$feedback1->keywords = '界面,用户体验,bug';
$feedback1->faq = '';
$feedback1->desc = '<p>用户反馈界面显示有问题，按钮位置不合理，影响操作体验。</p>';

// 创建最小化的反馈对象
$feedback2 = new stdClass();
$feedback2->id = 2;
$feedback2->title = '测试反馈2 - 功能建议';
$feedback2->feedbackBy = 'user002';
$feedback2->type = 'suggest';
$feedback2->pri = 3;
$feedback2->status = 'closed';
$feedback2->solution = 'implemented';
$feedback2->product = 1;
$feedback2->module = 2;
$feedback2->openedBy = 'admin';
$feedback2->openedDate = '2023-01-02 14:00:00';
$feedback2->assignedTo = 'product_manager';
$feedback2->assignedDate = '2023-01-02 15:00:00';
$feedback2->reviewedBy = 'admin';
$feedback2->reviewedDate = '2023-01-03 10:00:00';
$feedback2->closedBy = 'admin';
$feedback2->closedDate = '2023-01-05 16:00:00';
$feedback2->closedReason = 'resolved';
$feedback2->processedBy = 'product_manager';
$feedback2->processedDate = '2023-01-04 12:00:00';
$feedback2->source = 'email';
$feedback2->result = 'accepted';
$feedback2->keywords = '功能,建议,改进';
$feedback2->faq = '';
$feedback2->desc = '<p>建议增加批量操作功能，提高工作效率。</p>';

/* 测试转换反馈对象 */
$result1 = $zai->convertFeedbackToMarkdownTest($feedback1);
r($result1) && p('id') && e('1'); // 测试转换完整的反馈对象

$result2 = $zai->convertFeedbackToMarkdownTest($feedback2);
r($result2) && p('id') && e('2'); // 测试转换第二个反馈对象

/* 测试验证基本属性 */
r(isset($result1['attrs'])) && p() && e('1'); // 测试验证返回了attrs属性
r(isset($result2['attrs'])) && p() && e('1'); // 测试验证第二个对象返回了attrs属性

/* 测试验证内容生成 */
r(isset($result1['content']) && !empty($result1['content'])) && p() && e('1'); // 测试验证生成了content
r(isset($result2['content']) && !empty($result2['content'])) && p() && e('1'); // 测试验证第二个对象生成了content

/* 测试验证标题生成 */
r(isset($result1['title']) && !empty($result1['title'])) && p() && e('1'); // 测试验证生成了title
r(isset($result2['title']) && !empty($result2['title'])) && p() && e('1'); // 测试验证第二个对象生成了title

/* 验证返回数组结构正确 */
r(is_array($result1)) && p() && e('1'); // 测试返回数组结构
r(is_array($result2)) && p() && e('1'); // 测试第二个对象返回数组结构

/* 验证具体的属性值 */
r($result1['attrs']['product']) && p() && e('1'); // 测试验证产品属性
r($result1['attrs']['type']) && p() && e('bug'); // 测试验证类型属性

/* 测试验证反馈类型转换正确 */
r($result2['attrs']['type']) && p() && e('suggest'); // 测试验证反馈类型转换正确

/* 测试验证反馈状态处理 */
r($result1['attrs']['status']) && p() && e('active'); // 测试验证反馈状态处理

/* 测试验证反馈优先级设置 */
r($result1['attrs']['pri']) && p() && e('2'); // 测试验证反馈优先级设置
