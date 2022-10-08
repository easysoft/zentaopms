#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

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

r(count($personnel->getWhitelist(1,  'project'))) && p() && e('3'); //获取项目1白名单人员数量
r(count($personnel->getWhitelist('', 'project'))) && p() && e('0'); //当传入空时，查看匹配项目的人员数量
r(count($personnel->getWhitelist(9,  'program'))) && p() && e('1'); //获取项目集9白名单人员数量
