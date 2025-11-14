#!/usr/bin/env php
<?php

/**

title=测试 programZen::buildProgramForCreate();
timeout=0
cid=17724

- 执行programTest模块的buildProgramForCreateTest方法
 - 属性type @program
 - 属性name @测试项目集
 - 属性openedBy @admin
- 执行programTest模块的buildProgramForCreateTest方法 属性end @2059-12-31
- 执行programTest模块的buildProgramForCreateTest方法 属性type @program
- 执行programTest模块的buildProgramForCreateTest方法
 - 属性name @新项目集
 - 属性PM @user1
- 执行programTest模块的buildProgramForCreateTest方法
 - 属性openedBy @admin
 - 属性type @program

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

su('admin');

$programTest = new programTest();

$_POST['name'] = '测试项目集';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-12-31';
$_POST['PM'] = 'admin';
$_POST['budget'] = '100000';
$_POST['budgetUnit'] = 'CNY';
$_POST['acl'] = 'private';
$_POST['whitelist'] = array('admin', 'user1');
$_POST['desc'] = '这是测试描述';
$_POST['longTime'] = 0;
r($programTest->buildProgramForCreateTest()) && p('type,name,openedBy') && e('program,测试项目集,admin');

unset($_POST);
$_POST['name'] = '长期项目集';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-12-31';
$_POST['longTime'] = 1;
$_POST['acl'] = 'private';
r($programTest->buildProgramForCreateTest()) && p('end') && e('2059-12-31');

unset($_POST);
$_POST['name'] = '开放项目集';
$_POST['begin'] = '2024-01-01';
$_POST['end'] = '2024-12-31';
$_POST['acl'] = 'open';
$_POST['whitelist'] = array('admin', 'user1');
$_POST['longTime'] = 0;
r($programTest->buildProgramForCreateTest()) && p('type') && e('program');

unset($_POST);
$_POST['name'] = '新项目集';
$_POST['begin'] = '2024-02-01';
$_POST['end'] = '2024-11-30';
$_POST['PM'] = 'user1';
$_POST['acl'] = 'private';
$_POST['longTime'] = 0;
r($programTest->buildProgramForCreateTest()) && p('name,PM') && e('新项目集,user1');

unset($_POST);
$_POST['name'] = '验证默认值';
$_POST['begin'] = '2024-03-01';
$_POST['end'] = '2024-09-30';
$_POST['acl'] = 'private';
$_POST['longTime'] = 0;
r($programTest->buildProgramForCreateTest()) && p('openedBy,type') && e('admin,program');