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

$program = new programTest();

$pro= array('all', 'wait', 'doing', 'suspended', 'closed', 'name_desc', 'id_asc');

r($program->getList()) && p() && e('100'); // 查看所有项目和项目集的个数
r($program->getList()) && p() && e('34');  // 查看所有'wait'的项目和项目集的个数
r($program->getList()) && p() && e('44');  // 查看所有'doing'的项目和项目集的个数
r($program->getList()) && p() && e('11');  // 查看所有'suspended'的项目和项目集的个数
r($program->getList()) && p() && e('11');  // 查看所有'closed'的项目和项目集的个数
r($program->getList()) && p() && e(1);     // 按照项目和项目集名称倒序获取项目列表
r($program->getList()) && p() && e(1);     // 按照ID正序获取项目和项目集列表
