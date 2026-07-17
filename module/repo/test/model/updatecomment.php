#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->updateComment();
timeout=0
cid=0

- 更新评论内容 @Updated comment text
- 更新为SLUG格式文本 @SLUG-Format-Text-123
- 更新不存在的评论 @Missing comment
- 更新为中文评论 @中文评论测试
- 更新为特殊字符 @<b>bold</b>&

*/

su('admin');

zenData('action')->gen(10);

$repoTest = new repoModelTest();

r($repoTest->updateCommentTest(1, 'Updated comment text')) && p() && e('Updated comment text');  // 更新评论内容
r($repoTest->updateCommentTest(2, 'SLUG-Format-Text-123')) && p() && e('SLUG-Format-Text-123'); // 更新为SLUG格式文本
r($repoTest->updateCommentTest(999, 'Missing comment')) && p() && e('Missing comment');        // 更新不存在的评论
r($repoTest->updateCommentTest(3, '中文评论测试')) && p() && e('中文评论测试');              // 更新为中文评论
r($repoTest->updateCommentTest(4, '<b>bold</b>&')) && p() && e('<b>bold</b>&');              // 更新为特殊字符