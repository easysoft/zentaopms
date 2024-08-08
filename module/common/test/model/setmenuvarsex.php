#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 commonModel::setMenuVarsEx();
timeout=0
cid=1

- 查看替换后的菜单链接 @/execution-task-1
- 查看替换后的菜单链接属性link @/task-create-1

*/

$menu = "/execution-task-%s";
$menu = common::setMenuVarsEx($menu, 1);

r($menu) && p() && e('/execution-task-1'); // 查看替换后的菜单链接

$menu = array();
$menu['link'] = "/task-create-%s";
$menu = common::setMenuVarsEx($menu, 1);

r($menu) && p('link') && e('/task-create-1'); // 查看替换后的菜单链接