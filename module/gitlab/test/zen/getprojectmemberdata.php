#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::getProjectMemberData();
timeout=0
cid=0

- 执行gitlabTest模块的getProjectMemberDataTest方法，参数是array 第0条的0:user_id属性 @2
- 执行gitlabTest模块的getProjectMemberDataTest方法，参数是array 第0条的0:access_level属性 @40
- 执行gitlabTest模块的getProjectMemberDataTest方法，参数是array 第0条的0:expires_at属性 @2025-12-31
- 执行gitlabTest模块的getProjectMemberDataTest方法，参数是array 第2条的0:user_id属性 @2
- 执行gitlabTest模块的getProjectMemberDataTest方法，参数是array 第2条的0:access_level属性 @50
- 执行gitlabTest模块的getProjectMemberDataTest方法，参数是array 第1条的0属性 @2
- 执行gitlabTest模块的getProjectMemberDataTest方法，参数是array 第0条的0:user_id属性 @3
- 执行gitlabTest模块的getProjectMemberDataTest方法，参数是array 第1条的0属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

/* 设置 methodName 避免 gitlab 控制器构造函数报错 */
global $app;
$app->setMethodName('test');

$gitlabTest = new gitlabZenTest();

/* 准备测试数据 */
$member1 = new stdClass();
$member1->id = 1;
$member1->access_level = 30;
$member1->expires_at = '2025-12-31';

$member2 = new stdClass();
$member2->id = 2;
$member2->access_level = 40;
$member2->expires_at = '2025-12-31';

$member3 = new stdClass();
$member3->id = 3;
$member3->access_level = 50;
$member3->expires_at = '2025-12-31';

$member2Updated = new stdClass();
$member2Updated->id = 2;
$member2Updated->access_level = 50;
$member2Updated->expires_at = '2025-12-31';

/* 准备绑定用户和账号数据 */
$bindedUsers = array('user1' => 1, 'user2' => 2);
$accounts = array('user1', 'user2');
$originalUsers = array('user1', 'user2');

r($gitlabTest->getProjectMemberDataTest(array($member1), array(1 => $member1, 2 => $member2), $bindedUsers, $accounts, $originalUsers)) && p('0:0:user_id') && e('2');
r($gitlabTest->getProjectMemberDataTest(array($member1), array(1 => $member1, 2 => $member2), $bindedUsers, $accounts, $originalUsers)) && p('0:0:access_level') && e('40');
r($gitlabTest->getProjectMemberDataTest(array($member1), array(1 => $member1, 2 => $member2), $bindedUsers, $accounts, $originalUsers)) && p('0:0:expires_at') && e('2025-12-31');
r($gitlabTest->getProjectMemberDataTest(array($member1, $member2), array(1 => $member1, 2 => $member2Updated), $bindedUsers, $accounts, $originalUsers)) && p('2:0:user_id') && e('2');
r($gitlabTest->getProjectMemberDataTest(array($member1, $member2), array(1 => $member1, 2 => $member2Updated), $bindedUsers, $accounts, $originalUsers)) && p('2:0:access_level') && e('50');
r($gitlabTest->getProjectMemberDataTest(array($member1, $member2), array(1 => $member1), array('user1' => 1, 'user2' => 2), array('user1'), array('user1', 'user2'))) && p('1:0') && e('2');
r($gitlabTest->getProjectMemberDataTest(array($member1, $member2), array(2 => $member2Updated, 3 => $member3), array('user1' => 1, 'user2' => 2), array('user2'), array('user1', 'user2'))) && p('0:0:user_id') && e('3');
r($gitlabTest->getProjectMemberDataTest(array($member1, $member2), array(2 => $member2Updated, 3 => $member3), array('user1' => 1, 'user2' => 2), array('user2'), array('user1', 'user2'))) && p('1:0') && e('1');