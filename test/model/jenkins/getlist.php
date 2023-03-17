#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/jenkins.class.php';
su('admin');

/**

title=测试jenkinsModel->gitList();
cid=1
pid=1

获取Jenkins列表 >> 3

*/

$jenkins = new jenkinsTest();

$orderBy = 'id_desc';
r($jenkins->getList($orderBy)) && p('id') && e('3');    // 获取Jenkins列表

