#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/sso.class.php';
su('admin');

/**

title=ssoModel->createUser();
cid=1
pid=1

用户存在的情况 >> fail
用户信息错误的情况 >> fail
用户不存在的情况 >> bindUser1

*/

$sso = new ssoTest();

$existsUser = array(
    'account' => 'admin'
);

$newUser = array(
    'account'  => 'bindUser1',
    'realname' => '绑定用户1',
    'email'    => 'user@test.com',
    'gender'   => 'm',
    'ranzhi'   => 'bindUser1'
);

$failUser = array(
    'account'  => 'bindUser2',
    'realname' => '绑定用户2',
    'email'    => 'user@test.com',
    'gender'   => '',
    'ranzhi'   => 'bindUser2'
);

r($sso->createTest($existsUser)) && p('status')  && e('fail');      // 用户存在的情况
r($sso->createTest($failUser))   && p('status')  && e('fail');      // 用户信息错误的情况
r($sso->createTest($newUser))    && p('account') && e('bindUser1'); // 用户不存在的情况
