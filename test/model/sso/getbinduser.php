#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=ssoModel->getBindUser();
cid=1
pid=1

用户不存在绑定的情况 >> not user
用户存在绑定的情况 >> admin

*/

$sso = $tester->loadModel('sso');

$emptyUser = '';
$realUser  = 'admin';

$res = $sso->getBindUser($emptyUser);
if(!$res) $res = 'not user';
r($res)                          && p()          && e('not user'); // 用户不存在绑定的情况
r($sso->getBindUser($realUser))  && p('account') && e('admin');    // 用户存在绑定的情况