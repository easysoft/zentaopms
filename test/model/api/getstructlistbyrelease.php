#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->getStructListByRelease();
cid=1
pid=1

获取没有接口库的接口文档的名称 >> editStruct
获取所属接口库ID为1的接口文档的名称 >> user

*/

global $tester;
$api = new apiTest();

r($api->getStructListByReleaseTest('', 'where lib = 0')) && p('2:name') && e('editStruct'); // 获取没有接口库的接口文档的名称
r($api->getStructListByReleaseTest('', 'where lib = 1')) && p('0:name') && e('user');       // 获取所属接口库ID为1的接口文档的名称
