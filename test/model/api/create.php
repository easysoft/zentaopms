#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->create();
cid=1
pid=1

*/

$api = new apiTest();

$data = array(
    'lib'             => 910,
    'module'          => 0,
    'title'           => 'testapi',
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

$emptyLibApi = $data;
$emptyLibApi['lib'] = 0;

$emptyTitleApi = $data;
$emptyTitleApi['title'] = '';

$emptyPathApi = $data;
$emptyPathApi['path'] = '';

r($api->createTest($normalApi)) && p('lib') && e('910');                                        //测试创建api
r($api->createTest($emptyLibApi)) && p('lib:0') && e('『API Library』should not be blank.');    //测试没有lib创建api
r($api->createTest($emptyTitleApi)) && p('title:0') && e('『Name』should not be blank.');       //测试没有title创建api
r($api->createTest($emptyPathApi)) && p('path:0') && e('『Request Path』should not be blank.'); //测试没有path创建api
