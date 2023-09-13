#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';
su('admin');

zdTable('project')->gen(20);
zdTable('product')->gen(20);
zdTable('userview')->gen(5);

/**

title=测试 programModel::updateChildUserView();
timeout=0
cid=1

*/

$programTester = new programTest();

r($programTester->updateChildUserViewTest(0, array('test1', 'test2'))) && p('', '|') && e(',2'); // 获取项目集2下未完成的项目和项目集数量
r($programTester->updateChildUserViewTest(2, array('test1', 'test2'))) && p()        && e('0'); // 获取项目集1下未完成的项目和项目集数量
