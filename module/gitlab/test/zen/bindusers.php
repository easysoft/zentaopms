#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::bindUsers();
timeout=0
cid=0

- 测试新用户首次绑定,绑定成功第500条的account属性 @user1
- 测试用户已绑定且account未变化,不进行操作第100条的account属性 @user1
- 测试用户已绑定但account发生变化,先解绑再绑定第100条的account属性 @user2
- 测试绑定空account的情况,跳过绑定属性600 @~~
- 测试混合场景:新绑定、更新绑定、跳过空值第700条的account属性 @user4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('user')->loadYaml('user_bindusers', false, 2)->gen(10);
zenData('oauth')->loadYaml('oauth_bindusers', false, 2)->gen(5);

su('admin');

/* 设置 methodName 避免 gitlab 控制器构造函数报错 */
global $app;
$app->setMethodName('test');

$gitlabTest = new gitlabZenTest();

/* 准备测试数据 */
$gitlabID = 1;

/* 构造zentaoUsers数据 */
$user1 = new stdClass();
$user1->realname = '用户一';
$user2 = new stdClass();
$user2->realname = '用户二';
$user3 = new stdClass();
$user3->realname = '用户三';
$user4 = new stdClass();
$user4->realname = '用户四';

$zentaoUsers = array(
    'user1' => $user1,
    'user2' => $user2,
    'user3' => $user3,
    'user4' => $user4
);

r($gitlabTest->bindUsersTest($gitlabID, array(500 => 'user1'), array(500 => 'gitlab_user1'), $zentaoUsers)) && p('500:account') && e('user1'); // 测试新用户首次绑定,绑定成功
r($gitlabTest->bindUsersTest($gitlabID, array(100 => 'user1'), array(100 => 'gitlab_user1'), $zentaoUsers)) && p('100:account') && e('user1'); // 测试用户已绑定且account未变化,不进行操作
r($gitlabTest->bindUsersTest($gitlabID, array(100 => 'user2'), array(100 => 'gitlab_user1'), $zentaoUsers)) && p('100:account') && e('user2'); // 测试用户已绑定但account发生变化,先解绑再绑定
r($gitlabTest->bindUsersTest($gitlabID, array(600 => ''), array(600 => 'gitlab_user2'), $zentaoUsers)) && p('600') && e('~~'); // 测试绑定空account的情况,跳过绑定
r($gitlabTest->bindUsersTest($gitlabID, array(700 => 'user4', 800 => ''), array(700 => 'gitlab_user3', 800 => 'gitlab_user4'), $zentaoUsers)) && p('700:account') && e('user4'); // 测试混合场景:新绑定、更新绑定、跳过空值