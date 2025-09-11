#!/usr/bin/env php
<?php
/**

title=测试 customZen::checkKeysForSet();
timeout=0
cid=1

- 数据为空的返回值 @1
- 用户角色键值为过长数字的返回结果 @0
- 用户角色键值为过长数字的错误信息属性message @键的长度必须小于10个字符！
- 严重程度键值为字符串的返回结果 @0
- 严重程度键值为字符串错误信息属性message @键值应为不大于255的数字

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('lang')->gen(0);

global $tester;

$tester->app->setModuleName('custom');
$zen = initReference('custom');
$method = $zen->getMethod('checkKeysForSet');
$method->setAccessible(true);

$_POST['lang']    = 'zh-cn';
$_POST['keys']    = array();
$_POST['values']  = array();
$_POST['systems'] = array();
$result = $method->invokeArgs($zen->newInstance(), ['user','roleList']);
r($result) && p() && e(1); // 数据为空的返回值

$_POST['lang']    = 'zh-cn';
$_POST['keys']    = array('1234567891011121314151617181920');
$_POST['values']  = array('1234567891011121314151617181920');
$_POST['systems'] = array('1234567891011121314151617181920');
$result = $method->invokeArgs($zen->newInstance(), ['user','roleList']);
r($result) && p() && e(0);                                             // 用户角色键值为过长数字的返回结果
r(dao::getError()) && p('message') && e('键的长度必须小于10个字符！'); // 用户角色键值为过长数字的错误信息

$_POST['lang']    = 'zh-cn';
$_POST['keys']    = array('asdasd');
$_POST['values']  = array('asdasd');
$_POST['systems'] = array('asdasd');
$result = $method->invokeArgs($zen->newInstance(), ['bug','severityList']);
r($result) && p() && e(0);                                          // 严重程度键值为字符串的返回结果
r(dao::getError()) && p('message') && e('键值应为不大于255的数字'); // 严重程度键值为字符串错误信息
