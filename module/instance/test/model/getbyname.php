#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('instance')->gen(5);

/**

title=instanceModel->getByName();
timeout=0
cid=16800

- 查看获取到的第一条instance
- 查询 禅道开源版 是否存在 @禅道开源版
- 查询 禅道旗舰版 是否存在 @禅道旗舰版
- 查询 禅道企业版 是否存在 @禅道企业版
- 查询 解决方案100 是否存在 @0
- 查询 解决方案101 是否存在 @0
 */

global $tester;
$tester->loadModel('instance');

$instance = $tester->instance->getByName('禅道开源版');
r($instance) && p('name') && e('禅道开源版');

$instance = $tester->instance->getByName('禅道旗舰版');
r($instance) && p('name') && e('禅道旗舰版');

$instance = $tester->instance->getByName('禅道企业版');
r($instance) && p('name') && e('禅道企业版');

$instance = $tester->instance->getByName('解决方案100');
r(empty($instance)) && p('0') && e('0');

$instance = $tester->instance->getByName('解决方案101');
r(empty($instance)) && p('0') && e('0');
