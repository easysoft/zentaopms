#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->create();
cid=1
pid=1

测试创建api >> 920
测试没有lib创建api >>  『所属接口库』不能为空。
测试没有title创建api >> 『接口名称』不能为空。
测试没有path创建api >> 『请求路径』不能为空。

*/

$api = new apiTest();

$data = array(
    'lib'             => 920,
    'module'          => 0,
    'title'           => 'testapi',
    'protocol'        => 'HTTP',
    'method'          => 'GET',
    'path'            => '/api/test/id/test',
    'requestType'     => 'application/json',
    'status'          => 'done',
    'owner'           => 'admin',
    'type'            => 'formData',
    'params'          => '{"header":[],"params":[],"paramsType":"","query":[]}',
    'desc'            => '',
    'paramsExample'   => '',
    'response'        => '[]',
    'responseExample' => ''
);

$normalApi = $data;

$emptyLibApi = $data;
$emptyLibApi['lib'] = 0;

$emptyTitleApi = $data;
$emptyTitleApi['title'] = '';

$emptyPathApi = $data;
$emptyPathApi['path'] = '';

r($api->createTest($normalApi)) && p('lib') && e('920');                            //测试创建api
r($api->createTest($emptyLibApi)) && p('lib:0') && e('『所属接口库』不能为空。');   //测试没有lib创建api
r($api->createTest($emptyTitleApi)) && p('title:0') && e('『接口名称』不能为空。'); //测试没有title创建api
r($api->createTest($emptyPathApi)) && p('path:0') && e('『请求路径』不能为空。');   //测试没有path创建api
