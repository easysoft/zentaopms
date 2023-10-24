#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';
su('admin');

zdTable('acl')->gen(100);
zdTable('user')->gen(100);

/**

title=测试 personnelModel->getWhitelist();
cid=1
pid=1

获取项目1白名单人员数量 >> 3
当传入空时，查看匹配项目的人员数量 >> 0
获取项目集9白名单人员数量 >> 1

*/

global $tester;
$personnel = $tester->loadModel('personnel');

r(count($personnel->getWhitelist(1,  'project'))) && p() && e('5'); //获取项目1白名单人员数量
r(count($personnel->getWhitelist('', 'project'))) && p() && e('0'); //当传入空时，查看匹配项目的人员数量
r(count($personnel->getWhitelist(9,  'program'))) && p() && e('5'); //获取项目集9白名单人员数量
