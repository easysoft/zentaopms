#!/usr/bin/env php
<?php

/**

title=测试 cneModel->stopApp();
timeout=0
cid=1

- 启动应用
 - 属性code @200
 - 属性message @请求成功

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel  = new cneTest();

r($cneModel->stopAppTest()) && p('code,message') && e('200,请求成功'); // 启动应用