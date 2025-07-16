#!/usr/bin/env php
<?php

/**

title=测试adminModel->setMenu();
timeout=0
cid=0

- 执行admin模块的menuList方法
 - 第system条的name属性 @系统设置
 - 第system条的order属性 @5
- 执行admin模块的menuList方法
 - 第switch条的name属性 @功能开关
 - 第switch条的order属性 @10
- 执行admin模块的menuList方法
 - 第company条的name属性 @人员管理
 - 第company条的order属性 @15
- 执行admin模块的menuList方法
 - 第feature条的name属性 @功能配置
 - 第feature条的order属性 @25
- 执行admin模块的menuList方法
 - 第message条的name属性 @通知设置
 - 第message条的order属性 @35
- 执行admin模块的menuList方法
 - 第dev条的name属性 @二次开发
 - 第dev条的order属性 @45

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester,$lang;
$tester->loadModel('admin');
r($lang->admin->menuList) && p('system:name,order') && e('系统设置,5');
r($lang->admin->menuList) && p('switch:name,order') && e('功能开关,10');
r($lang->admin->menuList) && p('company:name,order') && e('人员管理,15');
$tester->admin->setMenu();
r($lang->admin->menuList) && p('feature:name,order') && e('功能配置,25');
r($lang->admin->menuList) && p('message:name,order') && e('通知设置,35');
r($lang->admin->menuList) && p('dev:name,order') && e('二次开发,45');
