#!/usr/bin/env php
<?php

/**

title=测试 storyModel->extractAccountsFromList();
cid=0

- 根据产品1的需求列表获取的accounts数量 @3
- 根据产品2的需求列表获取的accounts数量 @4
- 根据产品1的需求列表获取的account详情属性3 @user2
- 根据产品2的需求列表获取的account详情属性3 @user2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(20);

global $tester;
$tester->loadModel('story');
$stories1 = $tester->story->getProductStories(1);
$stories2 = $tester->story->getProductStories(2);

$accounts1 = $tester->story->extractAccountsFromList($stories1);
$accounts2 = $tester->story->extractAccountsFromList($stories2);

r(count($accounts1)) && p()    && e('3');     // 根据产品1的需求列表获取的accounts数量
r(count($accounts2)) && p()    && e('4');     // 根据产品2的需求列表获取的accounts数量
r($accounts1)        && p('3') && e('user2'); // 根据产品1的需求列表获取的account详情
r($accounts2)        && p('3') && e('user2'); // 根据产品2的需求列表获取的account详情
