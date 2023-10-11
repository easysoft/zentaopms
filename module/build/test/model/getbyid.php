#!/usr/bin/env php
<?php
/**

title=测试 buildModel->getByID();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/build.class.php';

zdTable('build')->config('build')->gen(20);
su('admin');

$buildID = array(7, 17);

$buildTester = new buildTest();
r($buildTester->getByIDTest($buildID[0], true))   && p('name')    && e('版本7');  // 项目版本查询
r($buildTester->getByIDTest($buildID[1], false))  && p('name')    && e('版本17'); // 执行版本查询
r($buildTester->getByIDTest(0, true))             && p()          && e('0');      // 无id查询
r($buildTester->getByIDTest($buildID[0], 'test')) && p('project') && e('61');     // 图片字段传字符串测试
