#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

/**

title=测试 repoModel->getList();
timeout=0
cid=0

- 获取代码库1的审批流程
 - 第1条的name属性 @review_flow1
 - 第1条的status属性 @enable
- 获取代码库2的审批流程数量 @1
- 获取代码库3的审批流程第3条的desc属性 @desc3
- 获取代码库4的审批流程第4条的name属性 @review_flow4
*/

zenData('repo')->gen(10);
zenData('ops_review_flow')->gen(10);

$repo = new repoTest();
r($repo->getReviewFlowListTest(1))        && p('1:name,status') && e('review_flow1,enable'); // 获取代码库1的审批流程
r(count($repo->getReviewFlowListTest(2))) && p()         && e('1');                          // 获取代码库2的审批流程数量
r($repo->getReviewFlowListTest(3))        && p('3:desc') && e('desc3');                      // 获取代码库3的审批流程
r($repo->getReviewFlowListTest(4))        && p('4:name') && e('review_flow4');               // 获取代码库4的审批流程
