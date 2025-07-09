#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$cron = zenData('cron');
$cron->command->range('1-5')->prefix('moduleName=instance&methodName=cronBackup&instanceID=');
$cron->gen(1);
zenData('instance')->loadYaml('instance')->gen(5);
zenData('space')->loadYaml('space')->gen(1);
zenData('user')->gen(5);

/**

title=instanceModel->autoBackup();
timeout=0
cid=1

- 执行instance模块的autoBackup方法，参数是$instance, $user  @1
- 执行instance模块的autoBackup方法，参数是$instance, $user  @1
- 执行instance模块的autoBackup方法，参数是$instance, $user  @1
- 执行instance模块的autoBackup方法，参数是$instance, $user  @1
- 执行instance模块的autoBackup方法，参数是$instance, $user  @1

*/

global $tester, $config;
$tester->loadModel('instance');

$config->CNE->api->host   = 'http://devops.corp.cc:32380';
$config->CNE->api->token  = 'R09p3H5mU1JCg60NGPX94RVbGq31JVkF';
$config->CNE->app->domain = 'devops.corp.cc';
$config->CNE->api->headers[] = "{$config->CNE->api->auth}: {$config->CNE->api->token}";

su('admin');
$user = new stdClass();
$user->account = 'admin';

$instance = $tester->instance->getByID(1);
r(!$tester->instance->autoBackup($instance, $user)) && p(0) && e(1);

$instance = $tester->instance->getByID(2);
r(!$tester->instance->autoBackup($instance, $user)) && p(0) && e(1);

$instance = $tester->instance->getByID(3);
r(!$tester->instance->autoBackup($instance, $user)) && p(0) && e(1);

$instance = $tester->instance->getByID(4);
r(!$tester->instance->autoBackup($instance, $user)) && p(0) && e(1);

$instance = $tester->instance->getByID(5);
r(!$tester->instance->autoBackup($instance, $user)) && p(0) && e(1);
