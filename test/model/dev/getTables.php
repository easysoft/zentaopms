#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dev.class.php';
su('admin');

/**

title=测试 devModel::getTables();
cid=1
pid=1

获取所有表my分组todo表 >> zt_todo
获取所有表product分组product表 >> zt_product
获取所有表other分组acl表 >> zt_acl

*/

$dev = new devTest();
r($dev->getTablesTest()) && p('my:todo')         && e('zt_todo');    //获取所有表my分组todo表
r($dev->getTablesTest()) && p('product:product') && e('zt_product'); //获取所有表product分组product表
r($dev->getTablesTest()) && p('other:acl')       && e('zt_acl');     //获取所有表other分组acl表