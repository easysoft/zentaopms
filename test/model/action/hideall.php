#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->hideAll();
cid=1
pid=1

隐藏回收站全部信息 >> 2;2;2

*/

$action = new actionTest();

r($action->hideAllTest()) && p('0:extra;1:extra;2:extra') && e('2;2;2'); // 隐藏回收站全部信息
