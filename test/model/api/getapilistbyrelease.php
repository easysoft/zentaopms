#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/api.class.php';
su('admin');

/**

title=测试 apiModel->getApiListByRelease();
cid=1
pid=1

创建api后创建发布，使用发布查找api >> 910

*/

global $tester;
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

$normalRelease = new stdclass();
$normalRelease->version   = 'Version1';
$normalRelease->desc      = '';
$normalRelease->lib       = 910;
$normalRelease->addedBy   = $tester->app->user->account;
$normalRelease->addedDate = helper::now();

$apiInfo = $api->createTest($normalApi, false);
$release = $api->publishLibTest($normalRelease, false);

r($api->getApiListByReleaseTest($release)) && p('0:lib') && e('910'); //创建api后创建发布，使用发布查找api