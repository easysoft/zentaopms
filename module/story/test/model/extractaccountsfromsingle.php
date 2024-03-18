#!/usr/bin/env php
<?php

/**

title=测试 storyModel->extractAccountsFromSingle();
cid=0

- 根据需求2获取的accounts数量 @2
- 根据需求4获取的accounts数量 @1
- 根据需求2获取的account详情 @user2
- 根据需求4获取的account详情 @dev4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('story')->gen(10);

global $tester;
$tester->loadModel('story');
$story1 = $tester->story->getById(2);
$story2 = $tester->story->getById(4);

$accounts1 = $tester->story->extractAccountsFromSingle($story1);
$accounts2 = $tester->story->extractAccountsFromSingle($story2);

r(count($accounts1)) && p()    && e('2');     // 根据需求2获取的accounts数量
r(count($accounts2)) && p()    && e('1');     // 根据需求4获取的accounts数量
r($accounts1[0])     && p('0') && e('user2'); // 根据需求2获取的account详情
r($accounts2[0])     && p('0') && e('dev4');  // 根据需求4获取的account详情
