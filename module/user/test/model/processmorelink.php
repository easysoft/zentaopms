#!/usr/bin/env php
<?php
/**
title=测试 userModel->processMoreLink();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $config;
$config->user->moreLink = '/user-ajaxGetMore';

$params        = 'product=1';
$usersToAppend = array('user1', 'user2', 'user3');

$userModel = $tester->loadModel('user');

$userModel->processMoreLink($params, $usersToAppend, 0, 0);
r($config->user) && p('moreLink') && e('``'); // maxCount 参数为 0，更多链接为空。

$userModel->processMoreLink($params, $usersToAppend, 1, 2);
r($config->user) && p('moreLink') && e('``'); // maxCount 参数不为 0 且小于 userCount 参数，更多链接为空。

$userModel->processMoreLink($params, $usersToAppend, 2, 1);
r($config->user) && p('moreLink') && e('``'); // maxCount 参数不为 0 且大于 userCount 参数，更多链接为空。

$config->webRoot     = '/';
$config->requestType = 'GET';
$userModel->processMoreLink($params, $usersToAppend, 2, 2);
r($config->user) && p('moreLink') && e('/processmorelink.php?m=user&f=ajaxGetMore&params=cGFyYW1zPXByb2R1Y3Q9MSZ1c2Vyc1RvQXBwZW5kZWQ9dXNlcjEsdXNlcjIsdXNlcjM='); // maxCount 参数不为 0 且等于 userCount 参数，访问类型为 GET，生成更多链接。

$config->requestType = 'PATH_INFO';
$userModel->processMoreLink($params, $usersToAppend, 2, 2);
r($config->user) && p('moreLink') && e('/user-ajaxGetMore.html?params=cGFyYW1zPXByb2R1Y3Q9MSZ1c2Vyc1RvQXBwZW5kZWQ9dXNlcjEsdXNlcjIsdXNlcjM='); // maxCount 参数不为 0 且等于 userCount 参数，访问类型为 PATH_INFO，生成更多链接。
