#!/usr/bin/env php
<?php

/**

title=测试 cneModel->getSettingsMapping();
timeout=0
cid=1

- 获取管理员账号信息
 - 属性admin_username @root
 - 属性z_username @zentao-bot
- 获取管理员账号信息
 - 属性admin_username @~~
 - 属性z_username @zentao-bot

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/cne.class.php';

zdTable('space')->config('space')->gen(2);
zdTable('solution')->config('solution')->gen(1);
zdTable('instance')->config('instance')->gen(2, true, false);

$cneModel = new cneTest();

r($cneModel->getSettingsMappingTest()) && p('admin_username,z_username') && e('root,zentao-bot'); // 获取管理员账号信息

$maps = array(
    array(
        "key"  => "z_username",
        "path" => "z_username",
        "type" => "secret"
    ),
    array(
        "key"  => "z_password",
        "path" => "z_password",
        "type" => "secret"
    )
);
r($cneModel->getSettingsMappingTest($maps)) && p('admin_username,z_username') && e('~~,zentao-bot'); // 获取管理员账号信息