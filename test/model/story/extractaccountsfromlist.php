#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->extractAccountsFromList();
cid=1
pid=1

根据产品1的需求列表获取的accounts数量 >> 2
根据产品2的需求列表获取的accounts数量 >> 2
根据产品1的需求列表获取的account详情 >> user2
根据产品2的需求列表获取的account详情 >> user2

*/

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getProductStories(1); 
$stories2 = $tester->story->getProductStories(2);

$accounts1 = $tester->story->extractAccountsFromList($stories1);
$accounts2 = $tester->story->extractAccountsFromList($stories2);

r(count($accounts1)) && p()    && e('2');     // 根据产品1的需求列表获取的accounts数量
r(count($accounts2)) && p()    && e('2');     // 根据产品2的需求列表获取的accounts数量
r($accounts1)        && p('2') && e('user2'); // 根据产品1的需求列表获取的account详情
r($accounts2)        && p('2') && e('user2'); // 根据产品2的需求列表获取的account详情