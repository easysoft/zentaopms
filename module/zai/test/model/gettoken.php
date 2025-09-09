#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->getZaiToken();
cid=0

- 执行zaiTest模块的getTokenTest方法 属性result @fail
- 执行zaiTest模块的getTokenTest方法 属性message @ZAI 配置不可用。
- 执行zaiTest模块的getTokenTest方法 属性message @非法禅道用户！
- 执行zaiTest模块的getTokenTest方法 属性result @success
- 执行zaiTest模块的getTokenTest方法 第data条的appID属性 @123
- 执行zaiTest模块的getTokenTest方法 第data条的userID属性 @admin
- 执行$result第data条的userID属性 @user1
- 执行$result['data']['hash'] == $hash @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('user')->gen(2);

$zaiTest = new zaiTest();

r($zaiTest->getTokenTest()) && p('result')  && e('fail');
r($zaiTest->getTokenTest()) && p('message') && e('ZAI 配置不可用。');

global $tester;
$zaiModel = $tester->loadModel('zai');
$zaiModel->config->zai->appToken = '123';
$zaiModel->config->zai->appID    = '123';
r($zaiTest->getTokenTest()) && p('message') && e('非法禅道用户！');

su('admin');
r($zaiTest->getTokenTest()) && p('result')      && e('success');
r($zaiTest->getTokenTest()) && p('data:appID')  && e('123');
r($zaiTest->getTokenTest()) && p('data:userID') && e('admin');

su('user1');
$result = $zaiTest->getTokenTest();
$hash   = md5($zaiModel->config->zai->appToken . $result['data']['appID'] . $result['data']['userID'] . $result['data']['expiredTime']);
r($result)                          && p('data:userID') && e('user1');
r($result['data']['hash'] == $hash) && p()              && e('1');