#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getStakeholdersByPrograms();
cid=1
pid=1

获取项目集2的干系人名单 >> user19
获取项目集2的干系人名单 >> user18
获取项目集2的干系人名单 >> user17
获取项目集2和项目集3的干系人名单 >> user19
获取项目集2和项目集3的干系人名单 >> user18
获取项目集2和项目集3的干系人名单... >> user17
获取项目集2的干系人个数 >> 3
获取项目集2和项目集3的干系人个数 >> 6

*/

$t = new Program('admin');

$Stakeholder = array('2', '2,3', '2', '2,3');

r($t->getByPrograms($Stakeholder[0]))   && p('0:account') && e('user19'); // 获取项目集2的干系人名单
r($t->getByPrograms($Stakeholder[0]))   && p('1:account') && e('user18'); // 获取项目集2的干系人名单
r($t->getByPrograms($Stakeholder[0]))   && p('2:account') && e('user17'); // 获取项目集2的干系人名单
r($t->getByPrograms($Stakeholder[1]))   && p('0:account') && e('user19'); // 获取项目集2和项目集3的干系人名单
r($t->getByPrograms($Stakeholder[1]))   && p('1:account') && e('user18'); // 获取项目集2和项目集3的干系人名单
r($t->getByPrograms($Stakeholder[1]))   && p('2:account') && e('user17'); // 获取项目集2和项目集3的干系人名单...
r($t->getCount4($Stakeholder[2]))       && p() && e('3'); // 获取项目集2的干系人个数
r($t->getCount4($Stakeholder[3]))       && p() && e('6'); // 获取项目集2和项目集3的干系人个数