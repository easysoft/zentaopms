#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);

su('admin');

/**

title=测试 messageModel->getBrowserMessageConfig();
cid=17050
pid=1

*/

$turnon   = array('0', '1');
$pollTime = array('300', '3001');

$message = new messageModelTest();

r($message->getBrowserMessageConfigTest($turnon[0], $pollTime[0])) && p('turnon,pollTime') && e('0,300');  // 测试获取 turnon 为 0 pollTime 为300 的浏览器设置信息
r($message->getBrowserMessageConfigTest($turnon[0], $pollTime[1])) && p('turnon,pollTime') && e('0,3001'); // 测试获取 turnon 为 0 pollTime 为3001 的浏览器设置信息
r($message->getBrowserMessageConfigTest($turnon[1], $pollTime[0])) && p('turnon,pollTime') && e('1,300');  // 测试获取 turnon 为 1 pollTime 为300 的浏览器设置信息
r($message->getBrowserMessageConfigTest($turnon[1], $pollTime[1])) && p('turnon,pollTime') && e('1,3001'); // 测试获取 turnon 为 1 pollTime 为3001 的浏览器设置信息
