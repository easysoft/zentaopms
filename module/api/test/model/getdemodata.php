#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 apiModel->getDemoData();
timeout=0
cid=1

- 获取数据结构表的初始化数据。
 - 第0条的id属性 @1
 - 第0条的lib属性 @853
 - 第0条的name属性 @user
 - 第0条的type属性 @json
- 获取模块表的初始化数据。
 - 第0条的id属性 @2964
 - 第0条的root属性 @853
 - 第0条的name属性 @工单
 - 第3条的id属性 @2961
 - 第3条的root属性 @853
 - 第3条的name属性 @项目

*/

global $tester;
$tester->loadModel('api');

r($tester->api->getDemoData('apistruct', '16.0')) && p('0:id,lib,name,type')            && e('1,853,user,json');             //获取数据结构表的初始化数据。
r($tester->api->getDemoData('module', '16.0'))    && p('0:id,root,name;3:id,root,name') && e('2964,853,工单,2961,853,项目'); //获取模块表的初始化数据。
