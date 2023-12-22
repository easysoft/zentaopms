#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';

su('admin');

/**

title=测试 transfer->getImportFields
timeout=0
cid=1

*/
$transfer = new transferTest();

r($transfer->getImportFieldsTest('task'))  && p('id;project;estimate;deadline') && e('编号,所属项目,最初预计,截止日期'); // 获取task模块的导入字段
r($transfer->getImportFieldsTest('story')) && p('id;product;source;reviewer')   && e('编号,所属产品,来源,评审人');       // 获取story模块的导入字段
