#!/usr/bin/env php
<?php

/**

title=测试adminModel->setSubMenu();
timeout=0
cid=0

- 获取key为system的子级导航菜单信息。
 - 属性name @系统设置
 - 属性order @5
- 获取key为switch的子级导航菜单信息。
 - 属性name @0
 - 属性order @0
- 获取key为company的子级导航菜单信息。
 - 属性name @人员管理
 - 属性order @15
- 获取key为feature的子级导航菜单信息。
 - 属性name @功能配置
 - 属性order @25
- 获取key为message的子级导航菜单信息。
 - 属性name @通知设置
 - 属性order @35

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester,$lang;
$tester->loadModel('admin');

r($tester->admin->setSubMenu('system', $lang->admin->menuList->system)) && p('name,order')  && e('系统设置,5');     // 获取key为system的子级导航菜单信息。
r($tester->admin->setSubMenu('switch', $lang->admin->menuList->switch)) && p('name,order')  && e('0,0');            // 获取key为switch的子级导航菜单信息。
r($tester->admin->setSubMenu('company', $lang->admin->menuList->company)) && p('name,order')  && e('人员管理,15');  // 获取key为company的子级导航菜单信息。
r($tester->admin->setSubMenu('feature', $lang->admin->menuList->feature)) && p('name,order')  && e('功能配置,25');  // 获取key为feature的子级导航菜单信息。
r($tester->admin->setSubMenu('message', $lang->admin->menuList->message)) && p('name,order')  && e('通知设置,35');  // 获取key为message的子级导航菜单信息。
