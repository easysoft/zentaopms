#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getStakeholders();
cid=1
pid=1

查看项目集2的干系人信息 >> 测试17
根据干系人id倒序排序 >> 1
根据干系人id正序排序 >> 1
查看项目集2的干系人个数 >> 3

*/

global $tester;
$tester->loadModel('program');
$stakeholders1 = $tester->program->getStakeholders(2, 'id_desc');
$stakeholders2 = $tester->program->getStakeholders(2, 'id_asc');

r(count($stakeholders1)) && p()             && e('3');      // 获取干系人数量
r(count($stakeholders2)) && p()             && e('3');      // 获取干系人数量
r($stakeholders1)        && p('0:realname') && e('测试17'); // id倒序排，获取第一个干系人真实姓名
r($stakeholders2)        && p('0:realname') && e('测试19'); // id正序排，获取第一个干系人真实姓名
