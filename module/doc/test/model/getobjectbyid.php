#!/usr/bin/env php
<?php
/**

title=测试 docModel->getObjectByID();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/doc.class.php';

zdTable('project')->config('execution')->gen(10);
zdTable('product')->config('product')->gen(10);
zdTable('user')->gen(5);
su('admin');

$types = array('all', 'product', 'project', 'execution');
$ids   = array(0, 1, 11, 101, 200);

$docTester = new docTest();
r($docTester->getObjectByIDTest($types[0], $ids[0])) && p()          && e('0');            // 测试空数据
r($docTester->getObjectByIDTest($types[1], $ids[0])) && p()          && e('0');            // 获取id=0的产品信息
r($docTester->getObjectByIDTest($types[1], $ids[1])) && p('id,name') && e('1,产品1');      // 获取id=1的产品信息
r($docTester->getObjectByIDTest($types[1], $ids[4])) && p()          && e('0');            // 获取不存在的产品信息
r($docTester->getObjectByIDTest($types[2], $ids[0])) && p()          && e('0');            // 获取id=0的项目信息
r($docTester->getObjectByIDTest($types[2], $ids[2])) && p('id,name') && e('11,敏捷项目1'); // 获取id=11的项目信息
r($docTester->getObjectByIDTest($types[2], $ids[4])) && p()          && e('0');            // 获取不存在的项目信息
r($docTester->getObjectByIDTest($types[3], $ids[0])) && p()          && e('0');            // 获取id=0的执行信息
r($docTester->getObjectByIDTest($types[3], $ids[3])) && p('id,name') && e('101,迭代5');    // 获取id=101的执行信息
r($docTester->getObjectByIDTest($types[3], $ids[4])) && p()          && e('0');            // 获取不存在的执行信息
