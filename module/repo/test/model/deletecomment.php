#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->deleteComment();
timeout=0
cid=0

- 删除存在的评论 @1
- 删除不存在的评论 @0
- 删除已删除的评论 @0
- 参数为0 @0
- 参数为负数 @0

*/

su('admin');

zendata('action')->loadYaml('action_starttask', false, 2)->gen(5);

$repoTest = new repoModelTest();

r($repoTest->deleteCommentTest(1)) && p() && e('1');    // 删除存在的评论
r($repoTest->deleteCommentTest(999)) && p() && e('0');  // 删除不存在的评论
r($repoTest->deleteCommentTest(1)) && p() && e('0');    // 删除已删除的评论
r($repoTest->deleteCommentTest(0)) && p() && e('0');    // 参数为0
r($repoTest->deleteCommentTest(-1)) && p() && e('0');   // 参数为负数