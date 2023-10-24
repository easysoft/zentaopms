#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->update();
cid=1
pid=1

修改一个刚创建的api >> edittestapi

*/

$api = new apiTest();

$data = array(
    'lib'             => 910,
    'module'          => 0,
    'title'           => 'testapi',
    'protocol'        => 'HTTP',
    'method'          => 'GET',
    'path'            => '/api/test/test',
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

$editData = array(
    'lib'             => 910,
    'module'          => 0,
    'title'           => 'edittestapi',
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
$editApi   = $editData;

$apiInfo = $api->createTest($normalApi, false);

r($api->updateTest($apiInfo->id, $editApi)) && p('0:new') && e('edittestapi'); //修改一个刚创建的api
