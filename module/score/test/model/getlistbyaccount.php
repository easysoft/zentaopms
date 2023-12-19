#!/usr/bin/env php
<?php
/**

title=测试 scoreModel->getListByAccount();
cid=1

- 获取5条admin积分记录第一页的数据 @5
- 获取10条admin积分记录第一页的数据 @10
- 获取20条admin积分记录第一页的数据 @20
- 获取5条admin积分记录第二页的数据 @5
- 获取10条admin积分记录第二页的数据 @10
- 获取20条admin积分记录第二页的数据 @20
- 获取5条user1积分记录第一页的数据 @5
- 获取10条user1积分记录第一页的数据 @10
- 获取20条user1积分记录第一页的数据 @20
- 获取5条user1积分记录第二页的数据 @5
- 获取10条user1积分记录第二页的数据 @10
- 获取20条user1积分记录第二页的数据 @20

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/score.class.php';

zdTable('user')->gen(5);
zdTable('score')->config('score')->gen(40);

$accounts    = array('admin', 'user1');
$recPerPages = array(5, 10, 20);
$pageIDs     = array(1, 2);

$scoreTester = new scoreTest();

/* Admin user. */
$adminScores1 = $scoreTester->getListByAccountTest($accounts[0], $recPerPages[0], $pageIDs[0]);
$adminScores2 = $scoreTester->getListByAccountTest($accounts[0], $recPerPages[1], $pageIDs[0]);
$adminScores3 = $scoreTester->getListByAccountTest($accounts[0], $recPerPages[2], $pageIDs[0]);
$adminScores4 = $scoreTester->getListByAccountTest($accounts[0], $recPerPages[0], $pageIDs[1]);
$adminScores5 = $scoreTester->getListByAccountTest($accounts[0], $recPerPages[1], $pageIDs[1]);
$adminScores6 = $scoreTester->getListByAccountTest($accounts[0], $recPerPages[2], $pageIDs[1]);

r(count($adminScores1)) && p() && e('5');  // 获取5条admin积分记录第一页的数据
r(count($adminScores2)) && p() && e('10'); // 获取10条admin积分记录第一页的数据
r(count($adminScores3)) && p() && e('20'); // 获取20条admin积分记录第一页的数据
r(count($adminScores4)) && p() && e('5');  // 获取5条admin积分记录第二页的数据
r(count($adminScores5)) && p() && e('10'); // 获取10条admin积分记录第二页的数据
r(count($adminScores6)) && p() && e('20'); // 获取20条admin积分记录第二页的数据

/* User1 user. */
$user1Scores1 = $scoreTester->getListByAccountTest($accounts[1], $recPerPages[0], $pageIDs[0]);
$user1Scores2 = $scoreTester->getListByAccountTest($accounts[1], $recPerPages[1], $pageIDs[0]);
$user1Scores3 = $scoreTester->getListByAccountTest($accounts[1], $recPerPages[2], $pageIDs[0]);
$user1Scores4 = $scoreTester->getListByAccountTest($accounts[1], $recPerPages[0], $pageIDs[1]);
$user1Scores5 = $scoreTester->getListByAccountTest($accounts[1], $recPerPages[1], $pageIDs[1]);
$user1Scores6 = $scoreTester->getListByAccountTest($accounts[1], $recPerPages[2], $pageIDs[1]);

r(count($user1Scores1)) && p() && e('5');  // 获取5条user1积分记录第一页的数据
r(count($user1Scores2)) && p() && e('10'); // 获取10条user1积分记录第一页的数据
r(count($user1Scores3)) && p() && e('20'); // 获取20条user1积分记录第一页的数据
r(count($user1Scores4)) && p() && e('5');  // 获取5条user1积分记录第二页的数据
r(count($user1Scores5)) && p() && e('10'); // 获取10条user1积分记录第二页的数据
r(count($user1Scores6)) && p() && e('20'); // 获取20条user1积分记录第二页的数据
