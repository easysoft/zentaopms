#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->addORPriv();
cid=19498

- 测试添加 or 界面权限，获取数量 @groups:4,groupPrivs:607

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->loadYaml('user')->gen(5);
zenData('company')->gen(1);
zenData('group')->gen(0);
zenData('grouppriv')->gen(0);

su('admin');

$upgrade = new upgradeModelTest();
r($upgrade->addORPrivTest()) && p() && e('groups:4,groupPrivs:607'); // 测试添加 or 界面权限，获取数量
