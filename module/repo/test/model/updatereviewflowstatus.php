#!/usr/bin/env php
<?php

/**

title=测试 repoModel::updateReviewFlowStatus();
timeout=0
cid=0

- 修改状态为enable
 - 属性status @enable
 - 属性name @review_flow1
- 修改状态为disable
 - 属性status @disable
 - 属性id @1
- 不存在的规则ID @0
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';
zenData('ops_review_flow')->gen(1);

$repo = new repoTest();

r($repo->updateReviewFlowStatusTest(1, 'enable'))  && p('status,name') && e('enable,review_flow1'); // 修改状态为enable
r($repo->updateReviewFlowStatusTest(1, 'disable')) && p('status,id')   && e('disable,1');           // 修改状态为disable
r($repo->updateReviewFlowStatusTest(0, 'enable'))  && p()              && e('0');                   // 不存在的规则ID

