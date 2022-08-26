#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getStakeholdersByPrograms();
cid=1
pid=1

获取项目集2的干系人名单 >> user19
获取项目集2的干系人名单 >> user18
获取项目集2的干系人名单 >> user17
获取项目集2和项目集3的干系人名单 >> user19
获取项目集2和项目集3的干系人名单 >> user18
获取项目集2和项目集3的干系人名单 >> user17
获取项目集2的干系人个数 >> 3
获取项目集2、3的干系人个数 >> 6

*/

global $tester;
$tester->loadModel('program');

r($tester->program->getStakeholdersByPrograms('2'))          && p('0:account') && e('user19'); // 获取项目集2的干系人名单
r($tester->program->getStakeholdersByPrograms('2'))          && p('1:account') && e('user18'); // 获取项目集2的干系人名单
r($tester->program->getStakeholdersByPrograms('2'))          && p('2:account') && e('user17'); // 获取项目集2的干系人名单
r($tester->program->getStakeholdersByPrograms('2,3'))        && p('0:account') && e('user19'); // 获取项目集2和项目集3的干系人名单
r($tester->program->getStakeholdersByPrograms('2,3'))        && p('1:account') && e('user18'); // 获取项目集2和项目集3的干系人名单
r($tester->program->getStakeholdersByPrograms('2,3'))        && p('2:account') && e('user17'); // 获取项目集2和项目集3的干系人名单
r(count($tester->program->getStakeholdersByPrograms('2')))   && p('')          && e('3');      // 获取项目集2的干系人个数
r(count($tester->program->getStakeholdersByPrograms('2,3'))) && p('')          && e('6');      // 获取项目集2、3的干系人个数