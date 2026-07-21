#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 repoModel->getReview();
timeout=0
cid=0

- 有效repoID无entry无revision @0
- repoID=0返回全部 @1
- 按entry过滤 @0
- 按revision过滤 @0
- 同时指定 @0

*/

su('admin');

zendata('bug')->loadYaml('bug_getcommitsbyobject', false, 2)->gen(5);
zendata('action')->loadYaml('action_starttask', false, 2)->gen(3);

$repoTest = new repoModelTest();

r($repoTest->getReviewTest(1)) && p() && e('0');         // 有效repoID无entry无revision
r($repoTest->getReviewTest(0)) && p() && e('1');         // repoID=0返回全部
r($repoTest->getReviewTest(1, 'test.php')) && p() && e('0');   // 按entry过滤
r($repoTest->getReviewTest(1, '', 'abc123')) && p() && e('0'); // 按revision过滤
r($repoTest->getReviewTest(1, 'test.php', 'abc123')) && p() && e('0'); // 同时指定