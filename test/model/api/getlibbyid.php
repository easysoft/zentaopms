#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->getLibById();
cid=1
pid=1

获取刚创建的api >> 910

*/

$api = new apiTest();

$data = array(
    'lib'             => 910,
    'module'          => 0,
    'title'           => '测试api',
    'protocol'        => 'HTTP',
    'method'          => 'GET',
    'path'            => '/api/test/id',
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

$apiInfo = $api->createTest($normalApi, false);
r($api->getLibByIdTest($apiInfo->id)) && p('lib') && e('910'); //获取刚创建的api