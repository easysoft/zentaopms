#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('case')->gen(30);
zenData('user')->gen(1);

su('admin');

/**

title=测试 testcaseTao->getReviewAmount();
cid=19045
pid=1

*/

$testcase = new testcaseTaoTest();

r($testcase->getReviewAmountTest()) && p() && e('8'); // 测试查询待评审的用例数量

su('user1');
r($testcase->getReviewAmountTest()) && p() && e('8'); // 测试查询待评审的用例数量

su('tester1');
r($testcase->getReviewAmountTest()) && p() && e('8'); // 测试查询待评审的用例数量

su('guest');
r($testcase->getReviewAmountTest()) && p() && e('8'); // 测试查询待评审的用例数量

su('po1');
r($testcase->getReviewAmountTest()) && p() && e('8'); // 测试查询待评审的用例数量