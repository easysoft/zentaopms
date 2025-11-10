#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::checkBindedUser();
timeout=0
cid=0

- 测试管理员用户调用，不检查绑定 @success
- 测试普通用户已绑定的情况 @success
- 测试普通用户未绑定的情况 @您还未绑定GitLab用户，请联系管理员进行绑定
- 测试不存在的gitlabID @您还未绑定GitLab用户，请联系管理员进行绑定
- 测试其他未绑定账号的情况 @您还未绑定GitLab用户，请联系管理员进行绑定

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);
zenData('pipeline')->gen(3);
$oauth = zenData('oauth');
$oauth->account->range('admin,user1,user2');
$oauth->openID->range('100,200,300');
$oauth->providerType->range('gitlab{3}');
$oauth->providerID->range('1{3}');
$oauth->gen(3);

su('admin');

/* 设置 methodName 避免 gitlab 控制器构造函数报错 */
global $app;
$app->setMethodName('test');

include dirname(__FILE__, 2) . '/lib/zen.class.php';

$gitlabTest = new gitlabZenTest();

r($gitlabTest->checkBindedUserTest(1, 'admin', true)) && p() && e('success'); // 测试管理员用户调用，不检查绑定
r($gitlabTest->checkBindedUserTest(1, 'user1', false)) && p() && e('success'); // 测试普通用户已绑定的情况
r($gitlabTest->checkBindedUserTest(1, 'user3', false)) && p() && e('您还未绑定GitLab用户，请联系管理员进行绑定'); // 测试普通用户未绑定的情况
r($gitlabTest->checkBindedUserTest(999, 'user1', false)) && p() && e('您还未绑定GitLab用户，请联系管理员进行绑定'); // 测试不存在的gitlabID
r($gitlabTest->checkBindedUserTest(1, 'user4', false)) && p() && e('您还未绑定GitLab用户，请联系管理员进行绑定'); // 测试其他未绑定账号的情况