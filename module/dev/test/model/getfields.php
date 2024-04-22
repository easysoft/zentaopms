#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';
su('admin');

/**

title=测试 devModel::getFields();
cid=1
pid=1

获取todo表字段名字 >> 开始
获取product表字段类型 >> varchar
获取acl表字段null属性 >> NO

*/

$dev = new devTest();
r($dev->getFieldsTest('zt_todo'))    && p('begin:name')   && e('开始');    //获取todo表字段名字
r($dev->getFieldsTest('zt_product')) && p('name:type')    && e('varchar'); //获取product表字段类型
r($dev->getFieldsTest('zt_acl'))     && p('account:null') && e('NO');      //获取acl表字段null属性