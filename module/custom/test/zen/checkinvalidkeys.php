#!/usr/bin/env php
<?php
/**

title=测试 customZen::checkInvalidKeys();
timeout=0
cid=1

- 数据为空的返回值 @0
- 用户角色键值为过长数字的错误信息 @键的长度必须小于10个字符！
- 待办类型键值为过长数字的错误信息 @键的长度必须小于15个字符！
- 任务类型键值为过长数字的错误信息 @键的长度必须小于20个字符！
- 严重程度键值为字符串的错误信息 @键值应为不大于255的数字

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('lang')->gen(0);

global $tester;

$tester->app->setModuleName('custom');
$zen = initReference('custom');
$method = $zen->getMethod('checkInvalidKeys');
$method->setAccessible(true);

$_POST['lang']    = 'zh-cn';
$_POST['keys']    = array();
$_POST['values']  = array();
$_POST['systems'] = array();
$result = $method->invokeArgs($zen->newInstance(), ['user','roleList']);
r($result) && p() && e(0); // 数据为空的返回值

$_POST['keys']    = array('1234567891011121314151617181920');
$_POST['values']  = array('1234567891011121314151617181920');
$result = $method->invokeArgs($zen->newInstance(), ['user','roleList']);
r($result) && p() && e('键的长度必须小于10个字符！'); // 用户角色键值为过长数字的错误信息

$result = $method->invokeArgs($zen->newInstance(), ['todo','typeList']);
r($result) && p() && e('键的长度必须小于15个字符！'); // 待办类型键值为过长数字的错误信息

$result = $method->invokeArgs($zen->newInstance(), ['task','typeList']);
r($result) && p() && e('键的长度必须小于20个字符！'); // 任务类型键值为过长数字的错误信息

$_POST['keys']    = array('asdasd');
$_POST['values']  = array('asdasd');
$result = $method->invokeArgs($zen->newInstance(), ['bug','severityList']);
r($result) && p() && e('键值应为不大于255的数字'); // 严重程度键值为字符串的错误信息
