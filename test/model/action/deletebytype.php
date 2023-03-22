#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->deleteByType();
cid=1
pid=1

测试删除object为空动态 >> 1
测试删除objectType为story的动态 >> 1
测试删除objectType为不存在的test的动态 >> 1

*/

$action = new actionTest();

r($action->deleteByTypeTest(''))      && p() && e('1');  // 测试删除object为空动态
r($action->deleteByTypeTest('story')) && p() && e('1');  // 测试删除objectType为story的动态
r($action->deleteByTypeTest('test'))  && p() && e('1');  // 测试删除objectType为不存在的test的动态
