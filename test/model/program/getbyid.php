#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getById();
cid=1
pid=1

通过id字段获取id=1的项目集并验证它的name。 >> 项目集1
通过id字段获取id=1000的项目集并验证它的name。 >> Not Found

*/

$getIdName = new Program('admin');

$t_verification = array('1', '1000');

r($getIdName->getById($t_verification[0]))    && p('name')    && e('项目集1');    // 通过id字段获取id=1的项目集并验证它的name。
r($getIdName->getById($t_verification[1]))    && p('message') && e('Not Found');  // 通过id字段获取id=1000的项目集并验证它的name。