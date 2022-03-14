#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getList();
cid=1
pid=1

查看所有项目和项目集的个数 >> 100
查看所有'wait'的项目和项目集的个数 >> 34
查看所有'doing'的项目和项目集的个数 >> 44
查看所有'suspended'的项目和项目集的个数 >> 11
查看所有'closed'的项目和项目集的个数 >> 11

*/

$program = new Program('admin');

$t_checkout = array('all', 'wait', 'doing', 'suspended', 'closed', 'name_desc', 'id_asc');

r($program->getListByStatus($t_checkout[0])) && p() && e('100'); // 查看所有项目和项目集的个数
r($program->getListByStatus($t_checkout[1])) && p() && e('34');  // 查看所有'wait'的项目和项目集的个数
r($program->getListByStatus($t_checkout[2])) && p() && e('44');  // 查看所有'doing'的项目和项目集的个数
r($program->getListByStatus($t_checkout[3])) && p() && e('11');  // 查看所有'suspended'的项目和项目集的个数
r($program->getListByStatus($t_checkout[4])) && p() && e('11');  // 查看所有'closed'的项目和项目集的个数
r($program->getListByOrder($t_checkout[5]))  && p() && e(1);     // 按照项目和项目集名称倒序获取项目列表
r($program->getListByOrder($t_checkout[6]))  && p() && e(1);     // 按照ID正序获取项目和项目集列表