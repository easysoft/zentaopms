#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->config('action')->gen(3);

/**

title=测试 actionModel->hideAll();
cid=1
pid=1

隐藏回收站全部信息 >> 2;2;2

*/

$action = new actionTest();

r($action->hideAllTest()) && p('0:extra;1:extra;2:extra') && e('2;2;2'); // 隐藏回收站全部信息
