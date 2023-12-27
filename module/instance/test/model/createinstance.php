#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('instance')->gen(1);

/**

title=instanceModel->createInstance();
timeout=0
cid=1

- 传入name，则使用name
 - 属性id @2
 - 属性name @测试创建实例
 - 属性desc @描述1
- 不传入name，则使用alias
 - 属性id @3
 - 属性name @zentao_1
 - 属性desc @描述1
- 修改domain
 - 属性id @4
 - 属性domain @r1z1.dops.corp.cc

*/

global $tester;
$tester->loadModel('instance');

$app = new stdclass();
$app->id            = 'zentao';
$app->alias         = 'zentao_1';
$app->name          = 'zentao';
$app->logo          = '/var/www/logo/zentao.png';
$app->desc          = '描述1';
$app->introduction  = '介绍1';
$app->chart         = '';
$app->app_version   = '11.0';
$app->version       = '11.0';

$space = new stdclass();
$space->id = 1;

$instance = $tester->instance->createInstance($app, $space, 'test', '测试创建实例');
r($instance) && p('id,name,desc') && e('2,测试创建实例,描述1'); // 传入name，则使用name

$instance = $tester->instance->createInstance($app, $space, 'test');
r($instance) && p('id,name,desc') && e('3,zentao_1,描述1'); // 不传入name，则使用alias

$instance = $tester->instance->createInstance($app, $space, 'r1z1');
r($instance) && p('id,domain') && e('4,r1z1.dops.corp.cc'); // 修改domain