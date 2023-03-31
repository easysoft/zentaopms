#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/pipeline.class.php';
su('admin');

/**

title=测试 pipelineModel->update();
cid=1
pid=1

获取修改后的name值 >> 测试修改gitlab服务器名称
当名称为空时 >> 『名称』不能为空。
url为空时 >> 『服务地址』不能为空。

*/

$pipeline = new pipelineTest();

$_POST['name'] = '测试修改gitlab服务器名称';
$_POST['url']  = 'http://www.zentaopms.com/upgrade.php';
$result1       = $pipeline->updateTest(1);

$_POST['name'] = '';
$result2       = $pipeline->updateTest(1);

$_POST['name'] = '测试修改gitlab服务器名称';
$_POST['url']  = '';
$result3       = $pipeline->updateTest(1);

unset($_POST);

r($result1) && p('name')   && e('测试修改gitlab服务器名称'); //获取修改后的name值
r($result2) && p('name:0') && e('『名称』不能为空。');       //当名称为空时
r($result3) && p('url:0')  && e('『服务地址』不能为空。');   //url为空时

