#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/pipeline.class.php';
su('admin');

/**

title=测试 pipelineModel->create();
cid=1
pid=1

获取添加的type >> Jenkins
获取添加的account >> JenkinsAccount
添加错误的url信息 >> 『服务地址』应当为合法的URL。
添加名称为空时 >> 『名称』不能为空

*/

$pipeline = new pipelineTest();

$data = new stdClass();
$data->name     = 'Jenkins';
$data->url      = 'http://www.zentaopms.com/';
$data->account  = 'JenkinsAccount';
$data->token    = 'Jenkins';
$data->password = 'Jenkins';

$result1 = $pipeline->createTest('Jenkins', $data); //获取添加的name
$result2 = $pipeline->createTest('Jenkins', $data); //获取添加的account

$data->url = 'error url';
$result3   = $pipeline->createTest('Jenkins', $data); //添加错误的url信息

$data->url  = 'http://www.zentaopms.com/';
$data->name = '';
$result4    = $pipeline->createTest('Jenkins', $data); //添加名称为空时

r($result1) && p('type')    && e('Jenkins');                       //获取添加的type
r($result2) && p('account') && e('JenkinsAccount');                //获取添加的account
r($result3) && p('url:0')   && e('『服务地址』应当为合法的URL。'); //添加错误的url信息
r($result4) && p('name:0')  && e('『名称』不能为空');              //添加名称为空时

