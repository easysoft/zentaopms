#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->gen(20);
zenData('product')->gen(20);
zenData('userview')->gen(5);

/**

title=测试 programModel::updateChildUserView();
timeout=0
cid=17713

- 更新项目集ID为0下的用户视图。 @,2
- 更新项目集ID为1下的用户视图。 @,2
- 更新项目集ID为2下的用户视图。 @0
- 更新项目集ID为3下的用户视图。 @0
- 更新项目集ID为4下的用户视图。 @0

*/

$programTester = new programModelTest();

r($programTester->updateChildUserViewTest(0, array('test1', 'test2'))) && p('', '|') && e(',2'); // 更新项目集ID为0下的用户视图。
r($programTester->updateChildUserViewTest(1, array('test1', 'test2'))) && p('', '|') && e(',2'); // 更新项目集ID为1下的用户视图。
r($programTester->updateChildUserViewTest(2, array('test1', 'test2'))) && p()        && e('0');  // 更新项目集ID为2下的用户视图。
r($programTester->updateChildUserViewTest(3, array('test1', 'test2'))) && p()        && e('0');  // 更新项目集ID为3下的用户视图。
r($programTester->updateChildUserViewTest(4, array('test1', 'test2'))) && p()        && e('0');  // 更新项目集ID为4下的用户视图。
