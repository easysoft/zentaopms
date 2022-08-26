#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/compile.class.php';
su('admin');

/**

title=测试 compileModel->getLastResult();
cid=1
pid=1

检查id存在的时候是否能拿到数据 >> admin
检查id不存在的时候返回的结果 >> 0

*/

$compile = new compileTest();

r($compile->getLastResultTest('1')) && p('createdBy') && e('admin'); //检查id存在的时候是否能拿到数据
r($compile->getLastResultTest('3')) && p('createdBy') && e('0');     //检查id不存在的时候返回的结果