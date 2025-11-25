#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';
su('admin');

// 直接插入测试数据，避免zendata问题
global $tester;
$tester->dao->delete()->from(TABLE_PROJECT)->where('type')->eq('program')->exec();
$tester->dao->insert(TABLE_PROJECT)->data(array(
    'id' => 1,
    'name' => '测试项目集1',
    'type' => 'program',
    'status' => 'doing',
    'openedBy' => 'admin',
    'openedDate' => '2023-01-01 00:00:00',
    'PM' => 'admin',
    'path' => ',1,',
    'grade' => 1,
    'parent' => 0,
    'deleted' => 0
))->exec();
$tester->dao->insert(TABLE_PROJECT)->data(array(
    'id' => 2,
    'name' => '测试项目集2',
    'type' => 'program',
    'status' => 'wait',
    'openedBy' => 'admin',
    'openedDate' => '2023-01-01 00:00:00',
    'PM' => 'admin',
    'path' => ',2,',
    'grade' => 1,
    'parent' => 0,
    'deleted' => 0
))->exec();

/**

title=测试 programModel::setMenu();
timeout=0
cid=17709

- 执行programTest模块的setMenuTest方法，参数是1  @1
- 执行programTest模块的setMenuTest方法  @1
- 执行programTest模块的setMenuTest方法，参数是999  @error
- 执行programTest模块的setMenuTest方法，参数是-1  @1
- 执行programTest模块的setMenuTest方法，参数是100000  @error

*/

$programTest = new programTest();

r($programTest->setMenuTest(1)) && p() && e(1);
r($programTest->setMenuTest(0)) && p() && e(1);
r($programTest->setMenuTest(999)) && p() && e('error');
r($programTest->setMenuTest(-1)) && p() && e(1);
r($programTest->setMenuTest(100000)) && p() && e('error');