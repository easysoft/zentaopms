#!/usr/bin/env php
<?php

/**

title=测试 kanbanZen::setUserAvatar();
timeout=0
cid=17001

- 执行kanbanTest模块的setUserAvatarTest方法 属性count @11
- 执行kanbanTest模块的setUserAvatarTest方法 属性hasClosed @1
- 执行kanbanTest模块的setUserAvatarTest方法 属性hasAvatar @1
- 执行kanbanTest模块的setUserAvatarTest方法 属性hasRealname @1
- 执行kanbanTest模块的setUserAvatarTest方法 属性count @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('user')->loadYaml('setuseravatar', false, 2)->gen(10);
su('admin');

$kanbanTest = new kanbanZenTest();

r($kanbanTest->setUserAvatarTest()) && p('count') && e('11');
r($kanbanTest->setUserAvatarTest()) && p('hasClosed') && e('1');
r($kanbanTest->setUserAvatarTest()) && p('hasAvatar') && e('1');
r($kanbanTest->setUserAvatarTest()) && p('hasRealname') && e('1');

zendata('user')->loadYaml('setuseravatar', false, 2)->gen(1);
r($kanbanTest->setUserAvatarTest()) && p('count') && e('2');